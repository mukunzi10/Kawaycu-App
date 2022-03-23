<template>
<div>
  <Header/>

  <div class="holder">
      <div class="content-wrapper" :style="{ 'background-image': 'url(' + bg + ')' }">
        <div class="container-fluid">
        </div>

          <div class="container-fluid ">
            <div class="row shadow-sm p-2 mb-2 rounded customHeight">
              <div class="col-md-12 col-lg-12  bg-white" style="padding:0px">
              
                 <div class="content-holder">
                    <div class="list-holder">
                      <div class="search-holder">
                          <input type="search"  @keyup="search($event)" placeholder="Enter search ...">
                      </div>
                      <div class="lists">
                           <div class="toggle-holder">
                             <button @click="toggle()" class="toggle text-primary">Show Recepient </button>
                            </div>
                             
                          <div class="lists" v-if="Trecepient">
                            <p class="list-title">List Recepient.</p>
                          <div class="cardi" v-for="(item,index) in recievers" v-bind:key="index">
                          <label>{{item.names}}</label>
                          <label>{{item.phone}}</label>
                          <label>{{item.type}}</label>
                          <label>{{item.site}}</label>
                           <button @click="remove(item.phone)"><i class="fa fa-remove text-danger" style="font-size:23px"></i></button>
                          </div>
                          </div>
                          <br/>
                          <p class="list-title">List Of All Farmers and Agents.</p>
                          <div class="cardi" v-for="(item,index) in data" v-bind:key="index">
                          <label>{{item.names}}</label>
                          <label>{{item.phone}}</label>
                          <label>{{item.type}}</label>
                          <label>{{item.site}}</label>
                           <button @click="addToList(item.phone)"><i class="fa fa-plus-circle text-primary" style="font-size:25px"></i>
                           
                        </button>
                          </div>
                       </div>

                    </div>

                    
                    <div class="sms-holder">
                        <div class="form-holder">
                            <form class="form">
                            <p class="list-title text-primary"><b>Compose Message:</b></p>
                            <br/>
                                <input type="text" v-model="message" placeholder="Message">
                              
                                <div class="forbutton">
                                <p v-if="submitting">Loading ...</p>
                                <button type="button" @click="submit()" >send</i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                 </div>
              </div>
            </div>
        </div>
      </div>
  </div>
</div>
</template>
<script>
import { required, minLength, between,number } from 'vuelidate/lib/validators';
import axios  from 'axios';
import Header from '../layout/header.vue'
export default {
  components: { Header},
    data(){
       return {
         
            data:[],
            copyData:[],
            recievers:[],
            searc_item:"",
            loading:false,
            submitting:false,
            message:"",
            Trecepient:false
       }
    },
    validations(){
            return {
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
             axios.get(`/api/farmersReport/all`,{
               headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
               }
             }).then(response=>{
                    let data=response.data.data;
                    data.map(item=>{
                        let itemData={
                            "names":item.farmer_firstname+" "+item.farmer_lastname,
                            "phone":item.farmer_phone,
                            "type":"Farmer",
                            "site":item.site_name
                         };
                       this.data.push(itemData);
                       this.copyData.push(itemData);
                    });
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
     loadAgents()
       {
         this.loading=true;

         let headers={
           'Accept':'application/json',
           'Authorization':'Bearer '+this.$store.state.user.token
          };
             axios.get(`/api/agents/all`,{
               headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
               }
             }).then(response=>{
                    let data=response.data.data;
                    data.map(item=>{
                        let itemData={
                            "names":item.agent_names,
                            "phone":item.agent_phone,
                            "type":"Agent",
                            "site":item.site_name
                         };
                       this.data.push(itemData);
                       this.copyData.push(itemData);
                    });
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
       submit()
       {
            
  
            this.submited = true;
            if (this.message=="") {
                return;
            } else {
                this.submitting = true;
            
             let formdata=new FormData();
            formdata.append('message',this.message);
            formdata.append('recepient',JSON.stringify(this.recievers));
                axios
                    .post("/api/sendSms", formdata)
                    .then(response => {
                        toast.fire({
                            icon: "success",
                            title: "Successfull Done!"
                        });
                        this.submitting=false;

                        
                    })
                    .catch(error => {
                        let errors = error.data;

                        this.loading = false;
                        if (error.response.status === 403) {
                            toast.fire({
                                icon: "error",
                                title: error.response.data.message
                            });
                        }
                    });
            }
        
       },
       addToList(phone)
       {
          let result=this.data.filter(item=>item.phone==phone);
          let check=this.recievers.filter(item=>item.phone==phone);
          
          if(!check.length>0)
          {
              this.recievers.push(...result);
              console.log(this.recievers);
          }

       },
       remove(phone)
       {
         
          let result=this.recievers.filter(item=>item.phone!=phone);
          console.log(result);
          this.recievers=result;
       },
       search(event)
       {
           let value=event.target.value;
           
           if(value.length>0)
           {
                let results=this.data.filter(item=>item.names.includes(value));
                this.data=results;
           }
           else
           {
               this.data=this.copyData;
           }
       },
       toggle()
       {
         this.Trecepient=!this.Trecepient;
       }
    },
    mounted()
    {
      let authData=JSON.parse(localStorage.getItem("data"));
      if(!authData)
      {
                window.location.href="/";
             }
      this.loadData();
      this.loadAgents();
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
.list-container{
    width:100%;
    min-height:500px;
    display:flex;
    flex-direction:column;
    padding:1rem;
}
.search-holder{
    width:100%;
    height:100px;
    display:flex;
    box-shadow:0px 0px 20px 0px rgba(0,0,0,0,0.19);
}
.search-holder input[type="search"]
{
    padding:10px;
    height:40px;
    width:80%;
    top:1rem;
    outline:none;
    margin-top:1rem;
    margin-left:4rem;
    box-shadow:0px 0px 10px 0px rgba(0,0,0,0.19);
}
.left-container{
  
    width:100px;
    padding:0px;
}
.content-holder{
    width:100%;
    padding:0px;
    min-height:500px;
    height:auto;
    display:flex;
}
.list-holder{
  width:60%;
  min-height:500px;
  height:auto;
  background:#f5fbff;

} 
.list-holder .lists{
    width:100%;
    height:auto;
    overflow-y:scroll;
    padding:10px;
    display:flex;
    flex-direction:column;
    

}
 .list-holder .lists .cardi{
   width:100%;
   padding:10px;
   height:50px;
   margin-top:1rem;
   background:white;
   display:flex;
   justify-content:space-around;
   align-items:center;
}
.list-holder .lists .cardi label{
   display:flex;
   width:100px;
   justify-content:center;
   align-items:center;
   font-size:15px;
}
.list-holder .lists .cardi button{
    padding:10px;
    cursor:pointer;
}
.list-title{
    width:100%;
    padding-left:1rem;
}
.sms-holder{
  width:39%;
  min-height:500px;
  height:auto;
  display:flex;
  flex-direction:column;
}
.receiver-holder{
    width:100%;
    height:300px;
    padding:10px;
    overflow:scroll;
    display:flex;
    flex-direction:column;
}
.form-holder{
    width:100%;
    min-height:500px;
    height:auto;
    display:flex;
}
.form{
    width:100%;
    min-height:250px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    padding:10px;
    margin-top:1rem;
}
.form .forbutton{
    width:100%;
    height:60px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    margin-top:2rem;
    box-shadow:0px 0px 10px 0px rgba(0,0,0,0,0.19);
}
.form .forbutton button{
    padding:5px;
    width:130px;
    cursor:pointer;
}
.form input[type="text"]{
  padding:10px;
  width:100%;
  height:100px;
  outline:none;
  box-shadow:0px 0px 10px 0px rgba(0,0,0,0.19);
}
.toggle-holder{
   width:100%;
   height:40px;
   display:flex;
   justify-content:flex-end;
}
.toggle{
  width:150px;
  padding:5px;
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