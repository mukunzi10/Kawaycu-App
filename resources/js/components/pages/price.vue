<template>
    <div>
        <Header />

        <!-- Modal -->
        <div
            class="modal fade"
            id="exampleModalCenter"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            Prices
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Amount</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="amount"
                                    v-model="amount"
                                    placeholder="Enter amount"
                                />
              

                                <span
                                    class="text-danger"
                                    v-if="submited && !$v.amount.required"
                                    >Description is required</span
                                >
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Site</label>

                                <select class="form-control" v-model="site">
                                    <option value="">Select Site</option>
                                    <option
                                        :value="item.site_id"
                                        v-for="(item, index) in sites"
                                        v-bind:key="index"
                                        >{{ item.site_name }}</option
                                    >
                                </select>
                                <span
                                    class="text-danger"
                                    v-if="submited && !$v.site.required"
                                    >Site is required</span
                                >
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div
                            class="pt-1 ml-4 mb-4 col-md-10 alignCenter"
                            v-if="loading"
                        >
                            <i class="fa fa-spinner fa-2x"></i>
                        </div>
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="save()"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="holder">
            <div class="content-wrapper " :style="{ 'background-image': 'url(' + bg + ')' }">
                <div class="container-fluid">
                    <div class="row shadow-sm p-1 mb-1 bg-white rounded">
                        <div class="col-md-2">Prices</div>
                        <div class="col-md-9">
                            <button
                                class="btn btn-primary pull-right"
                                data-toggle="modal"
                                data-target="#exampleModalCenter"
                            >
                               Add Price
                            </button>
                        </div>
                    </div>
                </div>

                <div class="container-fluid ">
                    <div class="row shadow-sm p-2 mb-2 rounded customHeight">
                        <div class="col-md-12 bg-white">
                            
                 <div class="col-md-12 bg-white">
                <br/>
                <datatable
                  :title="title"
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
.footer{
    height: 100px;
    width: 100%;
    color: brown;

}
.customHeight {
    min-height: 500px;
    background: #f6f5f6;
    margin-top: 20px;
}
.alignCenter {
    display: flex;
    justify-content: center;
    align-items: center;
}
.alignCenter i {
    animation: spinner 1s infinite;
}
@keyframes spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(300deg);
    }
}
@media (max-width: 500px) {
    .content-wrapper {
        width: 100%;
    }
}
</style>

<script>
import DataTable from "vue-materialize-datatable";
import { required, minLength, between, number } from "vuelidate/lib/validators";
import axios from "axios";
import Header from "../layout/header.vue";
export default {
    components: { Header,"datatable": DataTable},
    data() {
        return {
            bg:'storage/images/bg-1.jpg',
            data: [],
            loading: false,
            emptyTable: false,
            amount: "",
            site: "",
            submited: false,
            loading: false,
            sites: {},
            title:'List of Prices ',
            selectedOption: "all",
            Total: "0",
              tableColumns1: [

                {
                    label: "Site",
                    field: "site_name",
                    numeric: false,
                    html: false
                },
                {
                    label: "Initial Price",
                    field: "price_amount",
                    numeric: false,
                    html: false
                },
                {
                    label: "Date",
                    field: "created_at",
                    numeric: false,
                    html: false
                }
            ]
        };
    },
    validations() {
        return {
            amount: {
                required
            },
            site: {
                required
            },
        };
    },
    methods: {
        loadSites() {
            this.loading = true;
            let headers = {
                Accept: "application/json",
                Authorization: "Bearer " + this.$store.state.user.token
            };
            axios
                .get("/api/sites", {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.user.token
                    }
                })
                .then(response => {
                    this.sites = response.data.data;
                  
                    this.loading = false;
                    if (!this.data.length > 0) {
                        this.emptyTable = true;
                    }
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status === 403) {
                        toast.fire({
                            icon: "error",
                            title: error.response.data.message
                        });
                    }
                    if (error.response.status === 401) {
                        window.location.href = "/";
                    }
                });
        },
        loadData() {
            this.loading = true;

            let headers = {
                Accept: "application/json",
                Authorization: "Bearer " + this.$store.state.user.token
            };
            axios
                .get(`/api/price`, {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.user.token
                    }
                })
                .then(response => {
                    this.data = response.data.data;
                

                    this.loading = false;
                    if (!this.data.length > 0) {
                        this.emptyTable = true;
                    }
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status === 403) {
                        toast.fire({
                            icon: "error",
                            title: error.response.data.message
                        });
                    }
                    if (error.response.status === 401) {
                        window.location.href = "/";
                    }
                });
        },
        deletes(id) {
            axios
                .post(`/api/deleteAgent/${id}`, {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.user.token
                    }
                })
                .then(response => {
                    this.loadData();
                })
                .catch(error => console.log(error));
        },
        save() {
            this.$v.$touch();
            this.submited = true;
            if (this.$v.$invalid) {
                return;
            
            } else {
                this.loading = true;
                let formdata = new FormData();
                formdata.append("amount", this.amount);
                formdata.append("site", this.site);
                axios
                    .post("/api/createPrice", formdata)
                    .then(response => {
                        toast.fire({
                            icon: "success",
                            title: "Successfull Done!"
                        });
                        this.amount = "";
                        this.site = "";
                        $("#exampleModalCenter").modal("hide");
                        location.reload();
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
        filter() {
            this.loadData();
        }
    },
    mounted() {
        this.loadData();
        this.loadSites();
    }
};
</script>

<style scoped>
.avatar {
    width: 80px;
    height: 80px;
    border-radius: 50px;
    box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.19);
}
.form-group-style {
    width: 30%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 10px;
}
.filterContainer {
    display: flex;
    align-items: center;
}
.customCard {
    width: 200px;
    height: 60px;
    margin: 10px;
    background: dodgerblue;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
