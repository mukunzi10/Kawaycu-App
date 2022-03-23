<template>
    <div>
        <Header/>
        <div class="holder ">
            <div class="content-wrapper" :style="{ 'background-image': 'url(' + bg + ')' }">
                <div class="container-fluid">
                    <div class="row shadow-sm p-1 mb-1 bg-white rounded">
                        <div class="col-md-2">Dashboard</div>
                        <div class="col-md-9"></div>
                    </div>
                </div>

                <div class="container-fluid  ">
                    <div class="row shadow-sm p-2 mb-2 rounded customHeight bg-second">
                        <div class="col-md-12 bg-white">
                            <div class="row mt-5">


                            </div>
                            <div class="col-md-11 mt-5" v-if="!loading">
                                <ChartData :data="chart_data" />
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
import ChartData from "./ChartData.vue";
export default {
    components:{Header,ChartData},
    data()
    {
        return{
             harvest:'',
             farmers:'',
             sites:'',
             agents:'',
             paymentsAmount:'',
             Totalpayments:'',
             chart_data: [],
             loading:false,
              bg:'storage/images/bg-1.jpg',
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
                       this.chart_data=data;
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

</style>


