<template>
<div>
    <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Order Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form>
                <div class="view-group">
                    <label for="exampleInputEmail1">
                        <img :src="'/storage/images/'+proThumbnail" class="avatar">
                    </label>
                    
                </div>
                <ul class="list-group" v-for="(item,index) in data" v-bind:key="index">
                <li class="list-group-item disabled font-weight-bold">Product</li>
                <li class="list-group-item">{{item.product_name}}</li>
                <li class="list-group-item disabled font-weight-bold">Quantity</li>
                <li class="list-group-item">{{item.quantity}}</li>
                <li class="list-group-item disabled font-weight-bold">Unit Price</li>
                <li class="list-group-item">{{item.product_price}}</li>
                <li class="list-group-item disabled font-weight-bold">Total Price</li>
                 <li class="list-group-item">{{item.order_price}}</li>
                <li class="list-group-item disabled font-weight-bold">Order type</li>
                <li class="list-group-item">{{item.order_type}}</li>
                <li class="list-group-item disabled font-weight-bold">Order status</li>
                <li class="list-group-item">{{item.order_status}}</li>
            
                <li class="list-group-item disabled font-weight-bold" v-if="item.order_type=='delivery'">Address</li>
                <li class="list-group-item disabled " v-if="item.order_type=='delivery'">{{item.Address}}</li>

                <li class="list-group-item disabled font-weight-bold" v-if="item.order_type=='delivery'">Distance</li>
                <li class="list-group-item disabled " v-if="item.order_type=='delivery'">{{item.distance}}</li>

                <li class="list-group-item disabled font-weight-bold">Order Received status</li>
                <li class="list-group-item">{{item.received_status}}</li>
                
                </ul>
                <div class="pt-1 ml-4 mb-4 col-md-10 alignCenter" v-if="loading">
                    <i class="fa fa-spinner fa-2x"></i>
                </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" @click="processeOrder('rejected')">Reject Order</button>
        <button type="button" class="btn btn-primary" @click="processeOrder('processed')">Processed Order</button>
      </div>
    </div>
  </div>
</div>
  <Header/>

  <div class="holder">
      <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row shadow-sm p-1 mb-1 bg-white rounded">
              <div class="col-md-2">Orders</div>
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
                         <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Product name</th>
                        <th scope="col">Order type</th>
                        <th scope="col">Unit price</th>
                        <th scope="col">Order Quantity</th>
                        <th scope="col">Order Total Paid</th>
                        <th scope="col">Status</th>
                         <th scope="col">Operation</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item,index) in data" v-bind:key="index" class="trstyle">
                        <th scope="row">{{index+1}}</th>
                        <td>{{item.customer_name}}</td>
                        <td><img :src="'/storage/images/'+item.product_thumbnail" class="avatar"></td>
                        <td>{{item.product_name}}</td>
                        <td>{{item.order_type}}</td>
                        <td>{{item.product_price}}</td>
                        <td>{{item.quantity}}</td>
                        <td>{{item.order_price}}</td>
                         <td v-if="item.order_status=='processed'" class="text-success">{{item.order_status}}</td>
                         <td v-if="item.order_status=='rejected'" class="text-danger">{{item.order_status}}</td>
                         <td v-if="item.order_status=='pending'" class="text-info">{{item.order_status}}</td>
                        <td><a ><button @click="Detail(item.order_id)" data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary" style="height:50px"><i class="btn fa fa-eye text-white"></i></button></a></td>
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
            proThumbnail:'',
            detailData:{}
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
             axios.get('/api/orders',{
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
       Detail(id)
       {
           let filtered=this.data.filter(item=>item.order_id==id);
           this.detailData=filtered;
           filtered.map(item=>{
             this.proThumbnail=item.product_thumbnail;
           });
          $("#exampleModalCenter").modal('show');
       },
       processeOrder(status)
       {
         console.log(status,this.detailData[0].order_id);

                           this.loading=true;
                  let formdata=new FormData();
                  formdata.append('status',status);
                  formdata.append('order_id',this.detailData[0].order_id);
                  formdata.append('phone',this.detailData[0].customer_phone);
                  axios.post('/api/processedOrder',formdata).then(response=>{
                        toast.fire({
                          icon:"success",
                          title:'Successfull Done!'
                        });
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
 .view-group{
     width: 100%;
     min-height:100px;
     display:flex;
     align-items:center;
     justify-content:center;
 }
 .info-group{
     width: 100%;
     min-height:100px;

 }
</style>