<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import { useStore } from "vuex";

import { useRoute } from "vue-router";

const route = useRoute();
const store = useStore();

const empId = parseInt(route.params.id);

console.log(empId);

const official = ref({
  eid: store.state.employeeId || empId,
  departmentId: "",
  designationId: "",
  employeeGrade: "",
  areaId: "",
  territoryId: "",
  employeeTypeId: "",
  supervisorId: "",
  doj: "",
  provationPeriod: "",
  doc: "",
  jobLocation: "",
  shift: "",
});

const departments = ref([]);
const designations = ref([]);
const areas = ref([]);
const territories = ref([]);
const emptypes = ref([]);
const supervisor = ref([]);
const countries = ref([]);

const error = ref([]);
const empEdit = ref([]);



const getData = async () => {
  try {
    const responseDepartment = await axios.get("/api/department");
    const responseDesignation = await axios.get("/api/designation");
    const responseEmpType = await axios.get("/api/empType");
    const responseArea = await axios.get("/api/area");
    const responseTerritory = await axios.get("/api/territory");
    const responseSuper = await axios.get("/api/employee/allemp");
    const responseCountry = await axios.get("/api/phone");

    departments.value = responseDepartment.data;
    designations.value = responseDesignation.data;
    emptypes.value = responseEmpType.data;
    areas.value = responseArea.data;
    territories.value = responseTerritory.data;
    supervisor.value = responseSuper.data;
    countries.value = responseCountry.data;

    if (empId) {
      editHandler();
    }
  } catch (err) {
    error.value = err.message || "Error fetching data";
  } finally {
  }
};


watch(empEdit, (newEmpData) => {
  if (newEmpData) {
    official.value.departmentId = newEmpData.department.Name;
    official.value.designationId = newEmpData.designation.Name;
    official.value.employeeGrade = newEmpData.Employee_Grade;
    official.value.areaId = newEmpData.area.Name;
    official.value.territoryId = newEmpData.territory.Name;
    official.value.employeeTypeId = newEmpData.Employee_type_Id;
    official.value.supervisorId = newEmpData.supervisor.Full_Name;
    official.value.jobLocation = newEmpData.country.Name;
    official.value.doj = newEmpData.DOJ;
    official.value.doc= newEmpData.DOC;
    official.value.provationPeriod = newEmpData.Provation_period;
    official.value.shift = newEmpData.Shift;

    if (newEmpData.department) {
      official.value.departmentId = newEmpData.department.id;
    }
    if (newEmpData.designation) {
      official.value.designationId = newEmpData.designation.id;
    }
    if (newEmpData.area) {
      official.value.areaId = newEmpData.area.id;
    }
    if (newEmpData.territory) {
      official.value.territoryId = newEmpData.territory.id;
    }
    if (newEmpData.employeeType) {
      official.value.employeeTypeId = newEmpData.employeeType.id;
    }
    if (newEmpData.supervisor) {
      official.value.supervisorId = newEmpData.supervisor.id;
    }
    if (newEmpData.country) {
      official.value.jobLocation = newEmpData.country.id;
    }
  }
});



const resetForm = () => {
  Object.keys(employee.value).forEach((key) => {
    if (typeof employee.value[key] === "string") {
      employee.value[key] = "";
    }
  });
};

// const store = useStore();

const editHandler = async () => {
  try {
    const response = await axios.get(`/api/official/${empId}/edit`);
    empEdit.value = response.data;
  } catch (err) {
    console.error("Error fetching store data for editing:", err);
  }
};

const submitForm = async () => {
  try {
    const response = await axios.post("/api/official", official.value);
    if (response.data.success) {
      alert("Successfully Inserted");
      resetForm();
    }
  } catch (err) {
    console.error("Error submitting form:", err);
  }
};

const update = async () => {
  try {
    const response = await axios.put(`/api/official/${empId}`, official.value);
    if (response.data.success) {
      alert("Successfully Updated");
      resetForm();
    }
  } catch (error) {
    console.error("Error updating store:", error);
    // Handle the error, e.g., display an error message
  }
};

const submit = () => {
  if(empEdit.value == ""){
    submitForm();
  }
  else if (empId) {
    update();
  } else {
    submitForm();
  }
};


onMounted(() => getData());
</script>

<template>
  <div class="mt-5">
    <form @submit.prevent="submit">
      <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Department*</label>
          <select
            class="form-control"
            name="status"
            id=""
            v-model="official.departmentId"
          >
            <option selected disabled>select</option>
            <option
              v-for="dept in departments"
              :key="dept.id"
              :value="dept.id"
            >
              {{ dept.Name }}
            </option>
          </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Designation*</label>
          <select
            class="form-control"
            name="status"
            id=""
            v-model="official.designationId"
          >
            <option selected disabled>select</option>
            <option
              v-for="des in designations"
              :key="des.id"
              :value="des.id"
            >
              {{ des.Name }}
            </option>
          </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Employee Grade*</label>
          <select
            name=""
            id=""
            class="form-control"
            v-model="official.employeeGrade"
          >
            <option selected disabled value="">select</option>
            <option value="1">Grade 1</option>
            <option value="2">Grade 2</option>
            <option value="3">Grade 3</option>
            <option value="4">Grade 4</option>
            <option value="5">Grade 5</option>
            <option value="6">Grade 6</option>
            <option value="7">Grade 7</option>
            <option value="8">Grade 8</option>
            <option value="9">Grade 9</option>
            <option value="10">Grade 10</option>
            <option value="11">Grade 11</option>
            <option value="12">Grade 12</option>
            <option value="13">Grade 13</option>
            <option value="14">Grade 14</option>
            <option value="15">Grade 15</option>
            <option value="16">Grade 16</option>
            <option value="17">Grade 17</option>
            <option value="18">Grade 18</option>
            <option value="19">Grade 19</option>
            <option value="20">Grade 20</option>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Area</label>
          <select
            class="form-control"
            name="status"
            id=""
            v-model="official.areaId"
          >
            <option selected disabled>select</option>
            <option v-for="area in areas" :key="area.id" :value="area.id">
              {{ area.Name }}
            </option>
          </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Territory</label>
          <select
            class="form-control"
            name="status"
            id=""
            v-model="official.territoryId"
          >
            <option selected disabled>select</option>
            <option v-for="ter in territories" :key="ter.id" :value="ter.id">
              {{ ter.Name }}
            </option>
          </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Employee Type*</label>
          <select
            class="form-control"
            name="status"
            id=""
            v-model="official.employeeTypeId"
          >
            <option selected disabled>select</option>
            <option v-for="typ in emptypes" :key="typ.id" :value="typ.id">
              {{ typ.Name }}
            </option>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Supervisor</label>
          <select
            class="form-control"
            name="status"
            id=""
            v-model="official.supervisorId"
          >
            <option selected disabled>select</option>
            <option
              v-for="des in supervisor"
              :key="des.id"
              :value="des.id"
            >
              {{ des.Full_Name }}
            </option>
          </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Date of Joining</label>
          <input
            type="date"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="official.doj"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Date of Confirmation</label>
          <input
            type="date"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="official.doc"
          />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Probation Period</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="official.provationPeriod"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="" class="">Job Location</label>
          <select
            class="form-control"
            name="status"
            id=""
            v-model="official.jobLocation"
          >
            <option selected disabled>select</option>
            <option
              v-for="country in countries"
              :key="country.id"
              :value="country.id"
            >
              {{ country.Name }}
            </option>
          </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Shift</label>
          <select name="" id="" class="form-control" v-model="official.shift">
            <option selected disabled>select</option>
            <option value="D">Day</option>
            <option value="N">Night</option>
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-end">
        <button type="reset" class="btn btn-danger btn-lg m-3">Reset</button>
        <button type="submit" class="btn btn-primary btn-lg m-3">Save</button>
      </div>
    </form>
  </div>
</template>
