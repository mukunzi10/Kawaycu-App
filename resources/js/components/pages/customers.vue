<template>
<div>
  <Header/>

  <div class="holder">
      <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row shadow-sm p-1 mb-1 bg-white rounded">
              <div class="col-md-2">Customers</div>
              <div class="col-md-9">
               
              </div>
            </div>
        </div>

          <div class="container-fluid ">
            <div class="row shadow-sm p-2 mb-2 rounded customHeight">
              <div class="col-md-12 bg-white">
                <div class="table-responsive">
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Profile</th>
                        <th scope="col">Names</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Country code</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item,index) in data" v-bind:key="index" class="trstyle">
                        <th scope="row">{{index+1}}</th>
                        <td><img :src="'/storage/images/'+item.customer_profile" class="avatar"></td>
                        <td>{{item.customer_name}}</td>
                        <td>{{item.customer_phone}}</td>
                        <td>{{item.country_code}}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="pt-1 ml-4 mb-4 col-md-10 alignCenter" v-if="loading">
                    <i class="fa fa-spinner fa-2x"></i>
                  </div>
                  <div class="pt-1 ml-4 mb-4 col-md-10 alignCenter" v-if="emptyTable">
                    <small>No data</small>
                  </div>

                </div>
              </div>
            </div>
        </div>

      </div>
  </div>
</div>
</template>
<style lang="scss" scoped>
.holder{
  height:100%;
}
.content-wrapper{
  width:calc(100% - 240px);
  min-height:500px;
  float: right;
  height:auto;
  background:white;
  margin-top:1px;
  padding:10px;
  padding-top:20px;

}
.customHeight{
  min-height:500px;
  background:#f6f5f6;
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
@media (max-width:500px) {
  .content-wrapper{
    width:100%;
  }
}
</style>

<script>
import { required, minLength, between,number } from 'vuelidate/lib/validators';
import axios  from 'axios';
import Header from '../layout/header.vue'
export default {
  components: { Header},
    data(){
       return {
            data:{},
            loading:false,
            emptyTable:false
       }
    },
    methods:{
        loadData()
       {
         this.loading=true;

         let headers={
           'Accept':'application/json',
           'Authorization':'Bearer '+this.$store.state.user.token
          };
             axios.get('/api/customers',{
               headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
               }
             }).then(response=>{

                       this.data=response.data.data;
                        toast.fire({
                          icon:"success",
                          title:"Data fetched"
                        });   
                        this.loading=false;   
                      if(!this.data.length>0)
                       {
                           this.emptyTable=true;
                       }        
                  })
                  .catch(error=>{
                    this.loading=false;
                   if(error.response.status===403)
                    {
                        toast.fire({
                          icon:"error",
                          title:error.response.data.message
                        });
                    }
                    if(error.response.status===401)
                    {
                       window.location.href="/";
                    }

              });
       },
    },
    mounted()
    {
      this.loadData();
      let authData=JSON.parse(localStorage.getItem("data"));
      if(!authData)
          {
            window.location.href="/";
          }
    }
}
</script>

<style scoped>
 .avatar{
     width:80px;
     height:80px;
     border-radius:50px;
     box-shadow:0px 0px 5px 0px rgba(0, 0, 0, 0.19);
 }
</style>