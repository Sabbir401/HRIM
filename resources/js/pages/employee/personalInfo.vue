<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const route = useRoute();
const store = useStore();
const empId = parseInt(route.params.id);

const empEdit = ref(null);

const nominee = ref({
  eid: empId || store.state.employeeId,
  nomineeName: "",
  dob: "",
  contactNo: "",
  email: "",
  nid: "",
  share: "",
  presentAddress: "",
});

const child = ref({
  eid: empId || store.state.employeeId,
  childName: "",
  nid: "",
  email: "",
  contactNo: "",
  dob: "",
});

watch(empEdit, (newEmpData) => {
  if (newEmpData) {
    nominee.value.nomineeName = newEmpData.nominee[0].Nominee_Name;
    nominee.value.dob = newEmpData.nominee[0].DOB;
    nominee.value.contactNo = newEmpData.nominee[0].Contact_No;
    nominee.value.email = newEmpData.nominee[0].Email;
    nominee.value.nid = newEmpData.nominee[0].NID;
    nominee.value.share = newEmpData.nominee[0].Share;
    nominee.value.presentAddress = newEmpData.nominee[0].Personal_Address;
    //child Infromation
    child.value.childName = newEmpData.child[0].Child_Name;
    child.value.nid = newEmpData.child[0].NID;
    child.value.email = newEmpData.child[0].Email;
    child.value.contactNo = newEmpData.child[0].Contact_No;
    child.value.dob = newEmpData.child[0].DOB;
  }
});

const error = ref([]);

const getData = async () => {
  try {
    if (empId) {
      editHandler();
    }
  } catch (err) {
    error.value = err.message || "Error fetching data";
  } finally {
  }
};

const resetForm1 = () => {
  Object.keys(nominee).forEach((key) => {
    nominee[key] = "";
  });
};

const resetForm = () => {
  Object.keys(nominee.value).forEach((key) => {
    if (typeof nominee.value[key] === "string") {
        nominee.value[key] = "";
    }
  });
};

const editHandler = async () => {
  try {
    const response = await axios.get(`/api/nominee/${empId}/edit`);
    empEdit.value = response.data;
  } catch (err) {
    console.error("Error fetching store data for editing:", err);
  }
};

const submitForm = async () => {
  try {
    const requestData = {
      nominee: nominee.value,
      child: child.value,
    };
    const response = await axios.post("/api/nominee", requestData);
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
    const requestData = {
      nominee: nominee.value,
      child: child.value,
    };
    const response = await axios.put(`/api/nominee/${empId}`, requestData);
    if (response.data.success) {
      alert("Successfully Updated");
    }
  } catch (error) {
    console.error("Error updating store:", error);
    // Handle the error, e.g., display an error message
  }
};


const submit = async () => {
     console.log(empEdit.value);
  // if(empEdit.value.nominee && empEdit.value.nominee.length > 0 && empEdit.value.nominee[0].id){
  if(empEdit.value){
    console.log('update');
    update();
  } else {
    console.log('else');
    submitForm();
  }
};

onMounted(() => getData());
</script>



<template>
  <div class="mt-5">
    <h2 class="fs-4">Nominee Information</h2>
    <form @submit.prevent="submit">
      <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Nominee Name*</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="nominee.nomineeName"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Date of Birth*</label>
          <input
            type="date"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="nominee.dob"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Contact No*</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="nominee.contactNo"
          />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Email Id</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="nominee.email"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">National Id</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="nominee.nid"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Share %</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="nominee.share"
          />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label for="exampleInputEmail1" class="">Present Address</label>
          <textarea
            class="form-control"
            id="exampleFormControlTextarea1"
            rows="2"
            v-model="nominee.presentAddress"
          ></textarea>
        </div>
      </div>
      <hr class="border border-3 border border-dark" />

      <h2 class="fs-4">Child Information</h2>
      <div class="row mb-3">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Child Name</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="child.childName"
          />
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">NID/Birth Reg</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="child.nid"
          />
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Email Id</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="child.email"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Contact No</label>
          <input
            type="text"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="child.contactNo"
          />
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
          <label for="exampleInputEmail1" class="">Date of Birth</label>
          <input
            type="date"
            class="form-control"
            id="disabledTextInput"
            aria-describedby="emailHelp"
            v-model="child.dob"
          />
        </div>
      </div>

      <div class="d-flex justify-content-end">
        <button type="reset" class="btn btn-danger m-3 p-2"><i class="fa-solid fa-x"></i> | Reset</button>
        <button type="submit" class="btn btn-success m-3 p-2"><i class="fa-solid fa-check"></i> | Save</button>
      </div>
    </form>
  </div>
</template>


