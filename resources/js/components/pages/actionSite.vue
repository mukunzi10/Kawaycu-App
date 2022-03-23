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
                            Add Site
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
            <div class="content-wrapper" :style="{ 'background-image': 'url(' + bg + ')' }">
                <div class="container-fluid">
                    <div class="row shadow-sm p-1 mb-1 bg-white rounded">
                        <div class="col-md-2">Site</div>
                        <div class="col-md-9">
                            
                            </button>
                        </div>
                    </div>
                </div>

                <div class="container-fluid ">
                    <div class="row shadow-sm p-2 mb-2 rounded customHeight">
                        <div class="col-md-12 bg-white">
                                                    <form>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Name</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    v-model="name"
                                    placeholder="Enter names"
                                />
                                <span
                                    class="text-danger"
                                    v-if="submited && !$v.name.required"
                                    >Name is required</span
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Sector</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="desc"
                                    v-model="sector"
                                    placeholder="Enter sector"
                                />
                                <span
                                    class="text-danger"
                                    v-if="submited && !$v.sector.required"
                                    >Sector is required</span
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Cell</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="desc"
                                    v-model="cell"
                                    placeholder="Enter cell"
                                />
                                <span
                                    class="text-danger"
                                    v-if="submited && !$v.cell.required"
                                    >Cell is required</span
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Village</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="desc"
                                    v-model="village"
                                    placeholder="Enter village"
                                />
                                <span
                                    class="text-danger"
                                    v-if="submited && !$v.village.required"
                                    >Village is required</span
                                >
                            </div>
                            <br/>
                            <div
                            class="pt-1 ml-4 mb-4 col-md-10 alignCenter"
                            v-if="loading"
                        >
                            <i class="fa fa-spinner fa-2x"></i>
                        </div>
                        <button
                          @click='deletes()'
                            type="button"
                            class="btn bg-danger text-white"
                            
                        >
                            Delete
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="save()"
                        >
                            Update
                        </button>
                        </form>
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
    components: { Header,"datatable": DataTable },
    data() {
        return {
             bg:'storage/images/bg-1.jpg',
            data: [],
            title: "Site Lists available",
            loading: false,
            emptyTable: false,
            name: "",
            sector: "",
            cell: "",
            village: "",
            submited: false,
            loading: false,
            site_id:''
        };
    },
    validations() {
        return {
            name: {
                required
            },
            sector: {
                required
            },
            cell: {
                required
            },
            village: {
                required
            }
        };
    },
    methods: {

        onRowClick: function (row) {
        //row contains the clicked object from `rows`
        console.log(row)
      },
        loadData() {
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
        deletes() {
            axios
                .post(`/api/deleteSite/${this.site_id}`, {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.user.token
                    }
                })
                .then(response => {
                    this.$router.push('sites');
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
                formdata.append("name", this.name);
                formdata.append("sector", this.sector);
                formdata.append("cell", this.cell);
                formdata.append("village", this.village);
                formdata.append('id',this.site_id);
                axios
                    .post("/api/addsite", formdata)
                    .then(response => {
                        toast.fire({
                            icon: "success",
                            title: "Successfull Done!"
                        });
                        this.name = "";

                        this.$router.push('sites');
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
        }
    },
    mounted() {
                    let authData=JSON.parse(localStorage.getItem("data"));
             if(!authData)
             {
                window.location.href="/";
             }
        let datas=this.$route.params.data;
        console.log(datas);
        this.site_id=datas.site_id;
        this.village=datas.village;
        this.cell=datas.cell;
        this.name=datas.site_name;
        this.sector=datas.sector;
       
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
</style>
