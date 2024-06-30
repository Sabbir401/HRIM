<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const department = ref([]);
const empp = ref([]);
const emp_img = ref([]);
const daysInMonth = ref([]);
const selectedMonth = ref();

const months = ref([
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
]);

const error = ref([]);

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
        const responsedept = await axios.get("/api/department");
        department.value = responsedept.data;
    } catch (err) {
        error.value = err.message || "Error fetching data";
    } finally {
    }
};

const generateDays = () => {
    const year = new Date().getFullYear();
    const month = selectedMonth.value;
    const days = new Date(year, month + 1, 0).getDate(); 

    daysInMonth.value = Array.from({ length: days }, (v, k) => k + 1); 
};

const getEmployee = async (id) => {
    try {
        const response = await axios.get(`/api/emp/${id}`);
        empp.value = response.data;
        console.log(empp);
    } catch (error) {
        console.error("Error updating store:", error);
        // Handle the error, e.g., display an error message
    }
};

const getEmployeeImg = async (id) => {
    try {
        const responseimg = await axios.get(`/api/empimg/${id}`);
        emp_img.value = responseimg.data;
    } catch (error) {
        console.error("Error updating store:", error);
        // Handle the error, e.g., display an error message
    }
};

const resetForm = () => {
    Object.keys(form.value).forEach((key) => {
        if (typeof form.value[key] === "string") {
            form.value[key] = "";
        }
    });
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
            <div class="card mb-3">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="mb-5">Manual Attendance Form</h1>
                    </div>
                    <div class="d-flex">
                        <div class="col-lg-8">
                            <form class="forms-sample" @submit.prevent="submit">
                                <div class="row mb-3">
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
                                                >Month</label
                                            >
                                            <select
                                                class="form-control"
                                                name="status"
                                                id=""
                                                v-model="selectedMonth"
                                                @change="generateDays"
                                            >
                                                <option
                                                    v-for="(
                                                        month, index
                                                    ) in months"
                                                    :key="month"
                                                    :value="index"
                                                >
                                                    {{ month }}
                                                </option>
                                            </select>
                                        </div>
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
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table class="attendence">
                        <thead>
                            <tr>
                                <th>Emp ID</th>
                                <th>Emp Name</th>
                                <th>Designation</th>
                                <th v-for="day in daysInMonth" :key="day" class="text-center">
                                    {{ day }}
                                </th>
                                <th>Present</th>
                                <th>Sick</th>
                                <th>Personal</th>
                                <th>Day Off</th>
                                <th>Absent</th>
                                <th>Holiday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="e in empp" :key="e.id" :value="e.id">
                                <td>{{ e.Employee_Id }}</td>
                                <td>{{ e.Full_Name }}</td>
                                <td>{{ e.Name }}</td>
                                <th v-for="day in daysInMonth" :key="day">
                                    <select name="" id="">
                                        <option value="disable"></option>
                                        <option value="">P</option>
                                        <option value="">S</option>
                                        <option value="">C</option>
                                        <option value="">D</option>
                                        <option value="">A</option>
                                        <option value="">H</option>
                                    </select>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    </div>
</template>

<style>
.attendence tr,
.attendence th,
.attendence td {
    border: 1px solid black;
    padding: 4px;
}
.attendence input{
    border: none;
}
</style>
