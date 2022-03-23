<template>
<div class="accountbg" :style="{ 'background-image': 'url(' + bg + ')' }">
            <div class="content-center">
                <div class="content-desc-center">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5 col-md-8">
                                <div class="card">
                                    <div class="card-body">
                
                                        <h3 class="text-center mt-0 m-b-15">
                                            <!-- <a href="index.html" class="logo logo-admin"><img src="assets/images/logo-dark.png" height="30" alt="logo"></a> -->
                                        </h3>
                
                                        <h4 class="text-muted text-center font-18"><b>Sign In</b></h4>
                
                                        <div class="p-2">
                                            <form class="form-horizontal m-t-20">
                
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                      <input type="number" v-model="phone" class="form-control" placeholder="Phone" id="phone" aria-describedby="emailHelp">
                                                      <span class="text-danger" v-if="submited && !$v.phone.required">Phone is required</span>
                                                     <span class="text-danger" v-if="submited && !$v.phone.minLength">Phone number must be 10 digits</span>
                                                    </div>
                                                </div>
                
                                                <div class="form-group row">
                                                    <div class="col-12">
                                                        	<input type="password" placeholder="password" v-model="password" class="form-control" id="exampleInputPassword1">
                                                          <span class="text-danger" v-if="submited && !$v.password.required">Password is required</span>
                                                    </div>
                                                </div>
                                                  <div class="pt-1 ml-4 mb-4 col-md-10 alignCenter" v-if="loading">
                                                   <i class="fa fa-spinner fa-2x"></i>
                                                  </div>
                

                
                                                <div class="form-group text-center row m-t-20">
                                                    <div class="col-12">
                                                        <button class="btn btn-primary btn-block waves-effect waves-light" @click="login()" type="button">Log In</button>
                                                    </div>
                                                </div>
                
                                                <div class="form-group m-t-10 mb-0 row">
                                                    <div class="col-sm-7 m-t-20">
                                                        <a href="pages-recoverpw.html" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                                    </div>
                                                    <div class="col-sm-5 m-t-20">
                                                        <!-- <a href="pages-register.html" class="text-muted"><i class="mdi mdi-account-circle"></i> Create an account</a> -->
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import { required, minLength, between,number } from 'vuelidate/lib/validators';
import axios  from 'axios';
export default {
     data()
     {
         return{

             phone:'',
             password:'',
             submited:false,
             loading:false,
             bg:'storage/images/bg-1.jpg'

         }    
     },
    validations(){
            return {
                phone:{
                  required,
                  minLength:10
                  
                },
                password:{
                  required
                }
            }
        },
    methods:{
        login()
           {
                this.$v.$touch();
                this.submited=true;
                if (this.$v.$invalid) {
                    return;
                }
                else
                {    
                  this.loading=true;
                  let formdata=new FormData();
                  formdata.append('phone',this.phone);
                  formdata.append('password',this.password);
                  axios.post('/api/login',formdata).then(response=>{
                    let data=response.data.data;
                    this.$store.commit('logedIn',data);
                    this.loading=false;
                    localStorage.setItem("data",JSON.stringify(data));
                    if(data.type=="admin")
                    {
                        
                        this.$router.push('/dashboard');
                    }
                  })
                  .catch(error=>{
                    let errors=error.data;
                 
                    this.loading=false;
                    if(error.response.status===403)
                    {
                        toast.fire({
                          icon:"error",
                          title:error.response.data.message
                        });
                    }

                  });
                }
           }
        }
}
</script>
<style scoped lang="scss">
html,body { 
	height: 100%; 
}
.card{
  margin-top:100px;
}
.accountbg{
  height:100%;
  position:fixed;
  width:100%
}
.global-container{
	height:100%;
	display: flex;
	align-items: center;
    position: fixed;
    width:100%;
	justify-content: center;
	background-color: red;
  
}

form{
	padding-top: 10px;
	font-size: 14px;
	margin-top: 30px;
}

.card-title{ font-weight:300; }

.btn{
	font-size: 14px;
	margin-top:20px;
}


.login-form{ 
	width:330px;
	margin:20px;
}

.sign-up{
	text-align:center;
	padding:20px 0 0;
}

.alert{
	margin-bottom:-30px;
	font-size: 13px;
	margin-top:20px;
}
.alignCenter{
   display:flex;
   justify-content: center;
   align-items: center;
}
.alignCenter i{
  animation: spinner 1s infinite;
}
@keyframes spinner{
  0%{
    transform: rotate(0deg);
  }
  100%{
    transform: rotate(300deg);
  }
}
</style>