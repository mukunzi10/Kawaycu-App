<template>
<div>
  <Header/>



<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Site</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form>
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" id="name" v-model="name" placeholder="Enter names">
                    <span class="text-danger" v-if="submited && !$v.name.required">Name is required</span>
                </div>
                <div class="pt-1 ml-4 mb-4 col-md-10 alignCenter" v-if="loading">
                    <i class="fa fa-spinner fa-2x"></i>
                </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" @click="save()">Save</button>
      </div>
    </div>
  </div>
</div>
  <div class="holder">
      <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row shadow-sm p-1 mb-1 bg-white rounded">
              <div class="col-md-2">Sites</div>
              <div class="col-md-9">
                <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModalCenter">Add Site</button>
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
                        <th scope="col">Name</th>
                        <th scope="col" colspan="2">Operation</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item,index) in data" v-bind:key="index">
                        <th scope="row">{{index+1}}</th>
                        <td>{{item.site_name}}</td>
                        <td>{{item.created_at}}</td>
                        <!-- <td><a @click="edit(item.id,item.cource_name,item.cource_code,item.teacher,item.teacher_email)"><i class="btn fa fa-edit text-primary"></i></a></td> -->
                         <td><a @click="deletes(item.site_id)"><i class="btn fa fa-trash text-danger"></i></a></td>
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
            submited:false,
            loading:false
       }
    },
    validations(){
            return {
                name:{
                  required,
                  
                },
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
             axios.get('/api/sites',{
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
        deletes(id)
       {
          axios.post(`/api/deleteSite/${id}`,{
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
                else
                {  
                  this.loading=true;
                  let formdata=new FormData();
                  formdata.append('name',this.name);
                  axios.post('/api/addsite',formdata).then(response=>{
            
                        toast.fire({
                          icon:"success",
                          title:'Successfull Done!'
                        });
                        this.name="";
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
       }
    },
    mounted()
    {
      this.loadData();
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