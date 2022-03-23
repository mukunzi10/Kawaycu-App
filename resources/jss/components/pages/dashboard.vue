<template>
    <div>
        <Header/>
        <div class="holder">
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row shadow-sm p-1 mb-1 bg-white rounded">
                        <div class="col-md-2">Dashboard</div>
                        <div class="col-md-9"></div>
                    </div>
                </div>

                <div class="container-fluid ">
                    <div class="row shadow-sm p-2 mb-2 rounded customHeight">
                        <div class="col-md-12 bg-white">
                            <div class="row mt-5">
                                <div class="col-md-6 col-xl-4 sg">
                                    <div
                                        class="card shadow mb-3 widget-content bgblue"
                                    >
                                        <div
                                            class="widget-content-wrapper text-white"
                                        >
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    &nbsp;Total Sites
                                                </div>
                                                <div class="widget-subheading">
                                                    <small class="ml-2"></small>
                                                </div>
                                            </div>
                                            <div
                                                class="widget-content-right pull-right"
                                            >
                                                <div
                                                    class="widget-numbers text-white "
                                                >
                                                    <span class="mr-4">{{sites}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div
                                        class="card  shadow mb-3 widget-content bgdodger"
                                    >
                                        <div
                                            class="widget-content-wrapper text-white"
                                        >
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    &nbsp;Total Agents
                                                </div>
                                                <div class="widget-subheading">
                                                    <small class="ml-2"></small>
                                                </div>
                                            </div>
                                            <div
                                                class="widget-content-right pull-right"
                                            >
                                                <div
                                                    class="widget-numbers text-white"
                                                >
                                                    <span class="mr-4"
                                                        >{{agents}}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div
                                        class="card shadow mb-3 widget-content bgsuccess"
                                    >
                                        <div
                                            class="widget-content-wrapper text-white"
                                        >
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    &nbsp;Total Famers
                                                </div>
                                                <div class="widget-subheading">
                                                    <small class="ml-2"></small>
                                                </div>
                                            </div>
                                            <div
                                                class="widget-content-right pull-right"
                                            >
                                                <div
                                                    class="widget-numbers text-white"
                                                >
                                                    <span class="mr-4">{{farmers}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class="row mt-5">
                                <div class="col-md-6 col-xl-4 sg">
                                    <div
                                        class="card shadow mb-3 widget-content bg-info"
                                    >
                                        <div
                                            class="widget-content-wrapper text-white"
                                        >
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    &nbsp;Total Harvest
                                                </div>
                                                <div class="widget-subheading">
                                                    <small class="ml-2"></small>
                                                </div>
                                            </div>
                                            <div
                                                class="widget-content-right pull-right"
                                            >
                                                <div
                                                    class="widget-numbers text-white "
                                                >
                                                    <span class="mr-4"
                                                        >{{harvest}} Kg</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <div
                                        class="card  shadow mb-3 widget-content bg-warning"
                                    >
                                        <div
                                            class="widget-content-wrapper text-white"
                                        >
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    &nbsp;Total Payments
                                                </div>
                                                <div class="widget-subheading">
                                                    <small class="ml-2"></small>
                                                </div>
                                            </div>
                                            <div
                                                class="widget-content-right pull-right"
                                            >
                                                <div
                                                    class="widget-numbers text-white"
                                                >
                                                    <span class="mr-4"
                                                        >{{Totalpayments}}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                                                <div class="col-md-6 col-xl-4">
                                    <div
                                        class="card  shadow mb-3 widget-content bg-warning"
                                    >
                                        <div
                                            class="widget-content-wrapper text-white"
                                        >
                                            <div class="widget-content-left">
                                                <div class="widget-heading">
                                                    &nbsp;Total Payments Amounts
                                                </div>
                                                <div class="widget-subheading">
                                                    <small class="ml-2"></small>
                                                </div>
                                            </div>
                                            <div
                                                class="widget-content-right pull-right"
                                            >
                                                <div
                                                    class="widget-numbers text-white"
                                                >
                                                    <span class="mr-4"
                                                        >{{paymentsAmount}} RWF</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12 mt-5">
                                <!-- <Chartdata /> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.holder {
    height: 100%;
}
.content-wrapper {
    width: calc(100% - 240px);
    min-height: 500px;
    float: right;
    height: auto;
    background: white;
    margin-top: 1px;
    padding: 10px;
    padding-top: 20px;
}
.customHeight {
    min-height: 500px;
    background: #f6f5f6;
    margin-top: 20px;
}
.bgblue {
    background: #2b5276;
    color: white;
}
.bgdodger {
    background: #00d2ea;
    color: text;
}
.bgsuccess {
    background: #00af79;
    color: white;
}
@media (max-width: 500px) {
    .content-wrapper {
        width: 100%;
    }
}
</style>
<script>
import Header from "../layout/header.vue";
export default {
    components:{Header},
    data()
    {
        return{
             harvest:'',
             farmers:'',
             sites:'',
             agents:'',
             paymentsAmount:'',
             Totalpayments:''
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
             axios.get('/api/reports',{
               headers:{
                'Accept':'application/json',
                'Authorization':'Bearer '+this.$store.state.user.token
               }
             }).then(response=>{
                 
                       let data=response.data.data;
                       console.log(data);
                        toast.fire({
                          icon:"success",
                          title:"Data fetched"
                        });   

                        this.loading=false; 
                        this.harvest=data.harvest;
                        this.farmers=data.farmers;
                        this.sites=data.sites;
                        this.agents=data.agents;
                        this.paymentsAmount=data.totalPaymentAmounts;
                        this.Totalpayments=data.numberOfPayments;
                
                       
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
    }

}
</script>

