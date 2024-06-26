<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

const department = ref([]);
const empp = ref([]);
const emp_img = ref([]);

const error = ref([]);
const fileSizeWarning = ref();
const leaveType = ref();

const form = ref({
    department: "",
    Employee_Id: "",
    Time_In: "",
    Time_Out: "",
    From_Date: "",
    To_Date: "",
});

const getData = async () => {
    try {
        const [responsedept, responsetype] = await axios.all([
            axios.get("/api/department"),
            axios.get("/api/leave-type"),
        ]);
        department.value = responsedept.data;
        leaveType.value = responsetype.data;
    } catch (err) {
        error.value = err.message || "Error fetching data";
    } finally {
    }
};

const getEmployee = async (id) => {
    try {
        const response = await axios.get(`/api/emp/${id}`);
        empp.value = response.data;
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const getEmployeeImg = async (id) => {
    try {
        const responseimg = await axios.get(`/api/empimg/${id}`);
        emp_img.value = responseimg.data;
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const resetForm = () => {
    Object.keys(form.value).forEach((key) => {
        if (typeof form.value[key] === "string") {
            form.value[key] = "";
        } else {
            form.value[key] = null;
        }
    });
};

const getImage = (e) => {
    const file = e.target.files[0];
    if (file.size > 512000) {
        fileSizeWarning.value = true;
        e.target.value = null;
    } else {
        fileSizeWarning.value = false;
        employee.photo = e.target.files[0];
    }
};

const submit = async () => {
    try {
        const response = await axios.post("/api/attendence", form.value);
        if (response.data.success) {
            resetForm();
            alert("Successfully Inserted Attendence");
        }
    } catch (error) {
        console.error("Error submitting Attendence:", error);
    }
};

onMounted(() => getData());
</script>

<template>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="mb-5">Leave Apllication Form</h1>
                    </div>
                    <div class="d-flex">
                        <div class="col-lg-5">
                            <form class="forms-sample" @submit.prevent="submit">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Department</label
                                            >
                                            <select
                                                class="form-control"
                                                name="status"
                                                id=""
                                                v-model="form.department"
                                                @change="
                                                    getEmployee(form.department)
                                                "
                                            >
                                                <option selected disabled>
                                                    select
                                                </option>
                                                <option
                                                    v-for="dept in department"
                                                    :key="dept.id"
                                                    :value="dept.id"
                                                >
                                                    {{ dept.Name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Employee Name</label
                                            >
                                            <select
                                                class="form-control"
                                                name="status"
                                                id=""
                                                v-model="form.Employee_Id"
                                                @change="
                                                    getEmployeeImg(
                                                        form.Employee_Id
                                                    )
                                                "
                                            >
                                                <option
                                                    v-for="e in empp"
                                                    :key="e.id"
                                                    :value="e.id"
                                                >
                                                    {{ e.Full_Name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >From Date</label
                                            >
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="exampleInputEmail1"
                                                placeholder="Address"
                                                v-model="form.From_Date"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >To Date</label
                                            >
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="exampleInputEmail1"
                                                placeholder="Address"
                                                v-model="form.To_Date"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Prupose/Reason</label
                                            >
                                            <textarea
                                                class="form-control"
                                                id="exampleFormControlTextarea1"
                                                rows="2"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Leave Type</label
                                            >
                                            <select class="form-control" id="">
                                                <option selected disabled>
                                                    select
                                                </option>
                                                <option
                                                    v-for="leave in leaveType"
                                                    :key="leave.id"
                                                    :value="leave.id"
                                                >
                                                    {{ leave.Name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="exampleInputEmail1" class=""
                                            >Documents
                                            <span
                                                >(If sick leave is more than 2
                                                days)</span
                                            >
                                            <div
                                                v-if="fileSizeWarning"
                                                class="text-danger"
                                            >
                                                File size exceeds 500 KB. Please
                                                choose a smaller file.
                                            </div></label
                                        >
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="disabledTextInput"
                                            aria-describedby="emailHelp"
                                            @change="getImage"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <input
                                            type="submit"
                                            class="btn btn-success pl-2 form-control"
                                        />
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-3 d-flex justify-content-center">
                            <div v-for="i in emp_img" :key="i.id">
                                <img
                                    :src="i.img_url"
                                    height="100%"
                                    width="100%"
                                    style="max-height: 200px; max-width: 200px"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div v-for="i in emp_img" :key="i.id">
                                <table>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Entitled</th>
                                        <th>Enjoyed</th>
                                        <th>Balance</th>
                                    </tr>
                                    <tr>
                                        <th>Casual</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Sick</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Earned</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Other</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
th,
td {
    padding: 10px 3px;
    border: 1px solid black;
    text-align: center;
}
table {
    width: 100%;
}
</style>
