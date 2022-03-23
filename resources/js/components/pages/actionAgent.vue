<template>
    <div>
        <Header />
        <div class="holder">
            <div
                class="content-wrapper"
                :style="{ 'background-image': 'url(' + bg + ')' }"
            >
                <div class="container-fluid">
                    <div class="row shadow-sm p-2 mb-2 rounded customHeight">
                        <div class="col-md-12 bg-white">
                            <div class="col-md-12 bg-white">
                                <form>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"
                                            >Name</label
                                        >
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
                                        <label for="exampleInputEmail1"
                                            >Phone</label
                                        >
                                        <input
                                            type="number"
                                            min="1"
                                            class="form-control"
                                            id="desc"
                                            v-model="phone"
                                            placeholder="Enter phone"
                                        />
                                        <span
                                            class="text-danger"
                                            v-if="
                                                submited && !$v.phone.required
                                            "
                                            >Description is required</span
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"
                                            >Gender</label
                                        >
                                        <select
                                            class="form-control"
                                            v-model="gender"
                                        >
                                            <option value="">
                                                Select Gender
                                            </option>
                                            <option value="male">Male</option>
                                            <option value="female">
                                                Female
                                            </option>
                                        </select>
                                        <span
                                            class="text-danger"
                                            v-if="
                                                submited && !$v.gender.required
                                            "
                                            >Gender is required</span
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"
                                            >Site</label
                                        >

                                        <select
                                            class="form-control"
                                            v-model="site"
                                        >
                                            <option value="">
                                                Select Site
                                            </option>
                                            <option
                                                :value="item.site_id"
                                                v-for="(item, index) in sites"
                                                v-bind:key="index"
                                            >
                                                {{ item.site_name }}
                                            </option>
                                        </select>
                                        <span
                                            class="text-danger"
                                            v-if="submited && !$v.site.required"
                                            >Site is required</span
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"
                                            >Sector</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="desc"
                                            v-model="sector"
                                            placeholder="Enter sector"
                                        />
                                        <span
                                            class="text-danger"
                                            v-if="
                                                submited && !$v.sector.required
                                            "
                                            >Sector is required</span
                                        >
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"
                                            >Cell</label
                                        >
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
                                        <label for="exampleInputEmail1"
                                            >Village</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="desc"
                                            v-model="village"
                                            placeholder="Enter village"
                                        />
                                        <span
                                            class="text-danger"
                                            v-if="
                                                submited && !$v.village.required
                                            "
                                            >Village is required</span
                                        >
                                    </div>
                                </form>
                                <div
                                    class="pt-1 ml-4 mb-4 col-md-10 alignCenter"
                                    v-if="loading"
                                >
                                    <i class="fa fa-spinner fa-2x"></i>
                                </div>
                                <button
                                    @click="deletes()"
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
                                    <button
                                    type="button"
                                    class="btn btn-primary"
                                    @click="toggleStatus()"
                                >
                                    Toggle Status
                                </button>
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
.footer {
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
    components: { Header, datatable: DataTable },
    data() {
        return {
            bg: "storage/images/bg-1.jpg",
            data: {},
            loading: false,
            emptyTable: false,
            name: "",
            phone: "",
            gender: "",
            site: "",
            sector: "",
            cell: "",
            village: "",
            submited: false,
            loading: false,
            agent_id: "",
            sites: {},
            title: "List of Agents, Total number is : ",
            selectedOption: "all",
            Total: "0",
        };
    },
    validations() {
        return {
            name: {
                required,
            },
            phone: {
                required,
            },
            gender: {
                required,
            },
            site: {
                required,
            },
            sector: {
                required,
            },
            cell: {
                required,
            },
            village: {
                required,
            },
        };
    },
    methods: {
        loadSites() {
            this.loading = true;
            let headers = {
                Accept: "application/json",
                Authorization: "Bearer " + this.$store.state.user.token,
            };
            axios
                .get("/api/sites", {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.user.token,
                    },
                })
                .then((response) => {
                    this.sites = response.data.data;
                    toast.fire({
                        icon: "success",
                        title: "Data fetched",
                    });
                    this.loading = false;
                    if (!this.data.length > 0) {
                        this.emptyTable = true;
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    if (error.response.status === 403) {
                        toast.fire({
                            icon: "error",
                            title: error.response.data.message,
                        });
                    }
                    if (error.response.status === 401) {
                        window.location.href = "/";
                    }
                });
        },
        deletes() {
            axios
                .post(`/api/deleteAgent/${this.agent_id}`, {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.user.token,
                    },
                })
                .then((response) => {
                   this.$router.push('agents');
                })
                .catch((error) => console.log(error));
        },
     toggleStatus() {
            axios
                .post(`/api/status/${this.agent_id}`, {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + this.$store.state.user.token,
                    },
                })
                .then((response) => {
                   this.$router.push('agents');
                })
                .catch((error) => console.log(error));
        },
        save() {
            this.$v.$touch();
            this.submited = true;
            if (this.$v.$invalid) {
                return;
            } else if (this.file == "") {
                toast.fire({
                    icon: "error",
                    title: "Please Select image",
                });
            } else {
                this.loading = true;
                let formdata = new FormData();
                formdata.append("name", this.name);
                formdata.append("site", this.site);
                formdata.append("phone", this.phone);
                formdata.append("gender", this.gender);
                formdata.append("sector", this.sector);
                formdata.append("cell", this.cell);
                formdata.append("village", this.village);
                formdata.append("id", this.agent_id);
                axios
                    .post("/api/addAgent", formdata)
                    .then((response) => {
                        toast.fire({
                            icon: "success",
                            title: "Successfull Done!",
                        });
                        this.name = "";
                        this.gender = "";
                        this.phone = "";
                        this.site = "";
                        $("#exampleModalCenter").modal("hide");
                        this.$router.push('agents');
                    })
                    .catch((error) => {
                        let errors = error.data;
                        this.loading = false;
                        if (error.response.status === 403) {
                            toast.fire({
                                icon: "error",
                                title: error.response.data.message,
                            });
                        }
                    });
            }
        },
        filter() {
            this.loadData();
        },
    },
    mounted() {
        this.loadSites();
        let datas = this.$route.params.data;

        this.site = datas.site_id;
        this.village = datas.village;
        this.cell = datas.cell;
        this.name = datas.agent_names;
        this.sector = datas.sector;
        this.phone = datas.agent_phone;
        this.gender = datas.agent_gender;
        this.agent_id = datas.agent_id;
    },
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
