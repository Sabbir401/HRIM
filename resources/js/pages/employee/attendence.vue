<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import { Bootstrap5Pagination } from "laravel-vue-pagination";

import { useRoute } from "vue-router";

const employee = ref([]);
const department = ref([]);
const empp = ref([]);
const emp_img = ref([]);

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
    <div class="container">
      <div class="card">
        <div class="card-body">
          <div class="text-center">
            <h1 class="mb-5">Manual Attendance Form</h1>
          </div>
          <div class="d-flex">
            <div class="col-lg-8 shadow p-3">
              <form class="forms-sample" @submit.prevent="submit">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Department</label>
                      <select
                        class="form-control"
                        name="status"
                        id=""
                        v-model="form.department"
                        @change="getEmployee(form.department)"
                      >
                        <option selected disabled>select</option>
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
                      <label for="exampleInputEmail1">Employee Name</label>
                      <select
                        class="form-control"
                        name="status"
                        id=""
                        v-model="form.Employee_Id"
                        @change="getEmployeeImg(form.Employee_Id)"
                      >
                        <option v-for="e in empp" :key="e.id" :value="e.id">
                          {{ e.Full_Name }}
                        </option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Office Time In</label>
                      <input
                        type="time"
                        class="form-control"
                        id="exampleInputEmail1"
                        placeholder="Address"
                        v-model="form.Time_In"
                      />
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Office Time Out</label>
                      <input
                        type="time"
                        class="form-control"
                        id="exampleInputEmail1"
                        placeholder="Address"
                        v-model="form.Time_Out"
                      />
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">From Date</label>
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
                      <label for="exampleInputEmail1">To Date</label>
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
                    <input
                      type="submit"
                      class="btn btn-success pl-2 form-control"
                    />
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-4">
              <div v-for="i in emp_img" :key="i.id">
                <img
                  :src="i.img_url"
                  height="100%"
                  width="300px"
                  class="shadow"
                  style="padding-right: 20%; padding-left: 20%"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.shadow{
  box-shadow: 1px 1px 10px grey;
  border-radius: 5px;
}
</style>
