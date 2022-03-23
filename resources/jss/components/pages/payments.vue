<template>
<div>
  <Header/>

  <div class="holder">
      <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row shadow-sm p-1 mb-1 bg-white rounded">
              <div class="col-md-2">Payments</div>
            </div>
        </div>

          <div class="container-fluid ">
            <div class="row shadow-sm p-2 mb-2 rounded customHeight">
              <div class="col-md-12 bg-white">
              <div class="col-md-12 filterContainer" style="height:100px">
                   <label for="exampleInputEmail1">Filter: &nbsp;</label>
                  <div class="form-group form-group-style" style="display:flex">
                    
                    <select class="form-control" v-model="selectedOption" @change="filter()">
                        <option value="all">All</option>
                        <option :value="item.site_id" v-for="(item,index) in sites" v-bind:key="index">{{item.site_name}}</option>
                    </select>
                  </div>

                  <div class="customCard">
                     <p>Total Amount: {{Total}} RWF</p>
                  </div>
                 
              </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Names</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item,index) in data" v-bind:key="index" class="trstyle">
                        <th scope="row">{{index+1}}</th>
                        <td>{{item.farmer_firstname+" "+item.farmer_lastname}}</td>
                        <td>{{item.farmer_gender}}</td>
                        <td>{{item.farmer_phone}}</td>
                        <td>{{item.bTotal_amount}} RWF</td>
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
            emptyTable:false,
            name:'',
            phone:'',
            gender:'',
            site:'',
            submited:false,
            loading:false,
            sites:{},
            selectedOption:"all",
            Total:"0"
       }
    },
    validations(){
            return {
                name:{
                  required,
                  
                },
                phone:{
                    required
                },
                gender:{
                   required
                },
                site:{
                  required
                }
            }
        },
    methods:{

    loadSites()
       {
          this.loading=true;
          let headers={
           'Accept':'application/json',
           'Authorization':'Bearer '+this.$store.state.user.token
          };
             axios.get('/api/sites',{
               headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
               }
             }).then(response=>{
                       this.sites=response.data.data;
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
        loadData()
       {
         this.loading=true;
            let headers={
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
             };
             axios.get(`/api/adminBalanceReport/${this.selectedOption}`,{
               headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
               }
             }).then(response=>{

                       this.data=response.data.data;
                       this.Total=response.data.total;
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
        deletes(id)
       {
          axios.post(`/api/delete_product/${id}`,{
                                headers:{
                          'Accept':'application/json',
                          'Authorization':'Bearer '+this.$store.state.user.token
                    }
          }).then(response=>{
            this.loadData();
          }).catch(error=>console.log(error));
       },
       save()
       {
         
                this.$v.$touch();
                this.submited=true;
                if (this.$v.$invalid) {
                    return;
                }
                else if(this.file=="")
                {
                        toast.fire({
                          icon:"error",
                          title:'Please Select image'
                        });
                }
                else
                {  
                  this.loading=true;
                  let formdata=new FormData();
                  formdata.append('name',this.name);
                  formdata.append('site',this.site);
                  formdata.append('phone',this.phone);
                  formdata.append('gender',this.gender);
                  axios.post('/api/addAgent',formdata).then(response=>{
            
                        toast.fire({
                          icon:"success",
                          title:'Successfull Done!'
                        });
                        this.name="";
                        this.gender="";
                        this.phone="";
                        this.site="";
                        this.loadData();
                        $("#exampleModalCenter").modal('hide');
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
       },
       filter()
       {
         this.loadData();
       }
    },
    mounted()
    {
      this.loadData();
      this.loadSites();
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
 .form-group-style{
   width:30%;
   display:flex;
   justify-content: center;
   align-items: center;
   margin:10px;
 }
 .filterContainer{
   display:flex;
   align-items: center;
 }
 .customCard{
   width:200px;
   height:60px;
   margin:10px;
   background:dodgerblue;
   color:white;
   display:flex;
   justify-content: center;
   align-items:center;
  
 }

</style>