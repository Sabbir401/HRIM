<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import router from "../../router";

const employees = ref([]);
const isLoading = ref(true);
const error = ref(null);
const empEdit = ref(null);
const heading = ref(null);
const filter = ref("");
const results = ref("");

const getData = async (page = 1) => {
    try {
        const response = await axios.get(`/api/employee?page=${page}`);
        employees.value = response.data;
    } catch (err) {
        error.value = err.message || "Error fetching data";
    } finally {
        isLoading.value = false;
    }
};

const searchemp = async () => {
    if (filter.value.length > 0) {
        try {
            const response = await axios.get("api/employee/search", {
                params: {
                    query: filter.value,
                },
            });
            employees.value = response.data;
        } catch (error) {
            console.error(error);
        }
    } else {
        getData();
    }
};

const editHandler = async (id) => {
    try {
        const response = await axios.get(`api/employee/${id}/edit`);
        empEdit.value = response.data;
        heading.value = "Update";
        openModal();
    } catch (err) {
        console.error("Error fetching store data for editing:", err);
    }
};

const mapMaritalStatus = (status) => {
    const statusMap = {
        M: "Married",
        U: "Unmarried",
    };

    return statusMap[status] || "N/A";
};

const mapGender = (status) => {
    const statusMap = {
        M: "Male",
        F: "Female",
        O: "Other",
    };

    return statusMap[status] || "N/A";
};

const submitHandler = async () => {
    await getData();
};

onMounted(() => getData());
</script>

<template>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mr-auto p-1">
                        <h4 class="card-title m-2">Employee List</h4>
                    </div>
                    <div class="p-2">
                        <Router-Link :to="{ name: 'Employee' }">
                            <button
                                style="float: left"
                                type="button"
                                class="btn btn-success py-1"
                            >
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </Router-Link>
                    </div>

                    <div class="p-2">
                        <input
                            type="text"
                            class="form-control"
                            id="exampleInputUsername1"
                            placeholder="Search..."
                            v-model.trim="filter"
                        />
                    </div>
                    <div class="p-2">
                        <button
                            style="float: left"
                            type="button"
                            class="btn btn-success py-1"
                            @click="searchemp"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Employee ID</th>
                                <th>Full Name</th>
                                <th>Date of Birth</th>
                                <th>Contact</th>
                                <th>Official Email</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(employee, index) in employees.data"
                                :key="employee.id"
                            >
                                <td>{{ employees.from + index }}</td>
                                <td>{{ employee.Employee_Id }}</td>
                                <td>{{ employee.Full_Name }}</td>
                                <td>{{ employee.DOB }}</td>
                                <td>{{ employee.Contact_No }}</td>
                                <td>{{ employee.Official_Email }}</td>
                                <td>
                                    <button
                                        class="btn btn-primary mr-2 p-1 mx-2"
                                        @click="
                                            router.push(
                                                `/empdetails/${employee.id}`
                                            )
                                        "
                                    >
                                        <i class="fa-regular fa-user"></i>
                                    </button>
                                    <button
                                        class="btn btn-success p-1"
                                        @click="
                                            router.push(
                                                `/employee/${employee.id}`
                                            )
                                        "
                                    >
                                        <i
                                            class="fa-regular fa-pen-to-square"
                                        ></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br />
                    <Bootstrap5Pagination
                        :data="employees"
                        @pagination-change-page="getData"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
