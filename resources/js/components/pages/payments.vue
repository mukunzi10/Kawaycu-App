<template>
<div>
  <Header/>

  <div class="holder">
      <div class="content-wrapper" :style="{ 'background-image': 'url(' + bg + ')' }">


          <div class="container-fluid ">
            <div class="row shadow-sm p-2 mb-2 rounded customHeight">
              <div class="col-md-12 bg-white">
                <br/>
                <datatable
                  :title="title+Total"
                  :exportable="true"
                  :searchable="true"
                  :paginate="true"
                  :columns="tableColumns1"
                  :rows="data"
                />
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
import DataTable from "vue-materialize-datatable";
import { required, minLength, between,number } from 'vuelidate/lib/validators';
import axios  from 'axios';
import Header from '../layout/header.vue'
export default {
  components: { Header,"datatable": DataTable},
    data(){
       return {
         bg:'storage/images/bg-1.jpg',
            loading:false,
            title:"Balance on Payment Report, Total Amount is : RWF",
            emptyTable:false,
            name:'',
            phone:'',
            gender:'',
            site:'',
            submited:false,
            loading:false,
            sites:{},
            selectedOption:"all",
            Total:"0",
            month:"",
            payMethod:"",
        tableColumns1: [
			{
				label: "Names",
				field: "names",
				numeric: false,
				html: false
			},
			{
				label: "Gender",
				field: "gender",
				numeric: false,
				html: false
			},
			{
				label: "Phone",
				field: "phone",
				numeric: false,
				html: false
			},
			{
				label: "Amount",
				field: "bTotal_amount",
				numeric: true,
				html: false
			},
      {
				label: "Site",
				field: "site",
				numeric: true,
				html: false
			}
		],
		data: [],
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
                        this.loading=false;        
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
             axios.get(`/api/adminBalanceReport?option=${this.selectedOption}&month=${this.month}`,{
               headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
               }
             }).then(response=>{

                       let data=response.data.data;
                       this.Total=response.data.total; 
                       this.loading=false; 
                       data.map(item=>{
                         let itemData={
                            "names":item.farmer_firstname,
                            "gender":item.farmer_lastname,
                            "phone":item.farmer_phone,
                            "quantity":item.bTotal_amount,
                            "site":item.site_name,
                            'bTotal_amount':item.bTotal_amount
                         };
                         this.data.push(itemData);
                         
                       });
                       console
                          
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
    },
    mounted()
    {
                  let authData=JSON.parse(localStorage.getItem("data"));
             if(!authData)
             {
                window.location.href="/";
             }
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