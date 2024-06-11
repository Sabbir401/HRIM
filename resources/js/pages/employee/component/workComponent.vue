<script setup>
import {
  ref,
  onMounted,
  watch,
  defineProps,
  getCurrentInstance,
} from "vue";
import { onClickOutside } from "@vueuse/core";
import { useStore } from "vuex";
import axios from "axios";

const store = useStore();
const { isOpen, editStore, updateinfo, EID } = defineProps([
  "isOpen",
  "editStore",
  "updateinfo",
  "EID",
]);

const target = ref(null);
const error = ref([]);
const scales = ref([]);

const form = ref({
  eid: EID,
  companyName: "",
  companyBusiness: "",
  companyAddress: "",
  fromDate: "",
  toDate: "",
  designation: "",
  department: "",
  jobRes: "",
  lastSalary: "",
  continuing: "",
  remarks: "",
});

const instance = getCurrentInstance();

watch(
  () => editStore,
  (newVal) => {
    form.id = newVal.id;
  }
);

const getData = async () => {
  try {
    const responseScale = await axios.get("/api/scale");

    scales.value = responseScale.data;
  } catch (err) {
    error.value = err.message || "Error fetching data";
  } finally {
  }
};

onMounted(() => {
  onClickOutside(target, () => instance.emit("modal-close")), getData();
});

const closeModal = () => {
  instance.emit("modal-close");
};

const resetForm = () => {
  Object.keys(form).forEach((key) => {
    form[key] = "";
  });
};

const create = async () => {
  try {
    const response = await axios.post("/api/work", form.value);
    if (response.data.success) {
      instance.emit("modal-close");
      alert("Successfully Inserted");
      resetForm();
    }
  } catch (error) {
    console.error("Error creating store:", error);
    // Handle the error, e.g., display an error message
  }
};

const update = async () => {
  try {
    const response = await axios.put(`/api/store/${form.id}`, form);
    if (response.data.success) {
      instance.emit("modal-close");
      alert("Successfully Updated");
      resetForm();
    }
  } catch (error) {
    console.error("Error updating store:", error);
    // Handle the error, e.g., display an error message
  }
};

const submit = () => {
  if (updateinfo === "Save") {
    create();
  } else if (updateinfo === "Update") {
    update();
  } else {
    console.error("Invalid updateinfo value:", updateinfo);
  }
};
</script>

<template>
  <div
    v-if="isOpen"
    class="modal-mask col-md-6 grid-margin stretch-card"
    ref="target"
  >
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Work Experience</h4>
        <form class="forms-sample" @submit.prevent="create">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Company name</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder=""
                  v-model="form.companyName"
                />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Company Business</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.companyBusiness"
                />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Company Address</label>
                <textarea
                  name=""
                  id=""
                  cols="30"
                  rows="3"
                  class="form-control"
                  placeholder="Address ..."
                  v-model="form.companyAddress"
                ></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">From Date</label>
                <input
                  type="date"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.fromDate"
                />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">To Date</label>
                <input
                  type="date"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.toDate"
                />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Designation</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder=""
                  v-model="form.designation"
                />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Department</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.department"
                />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Job Resposibilities</label>
                <textarea
                  name=""
                  id=""
                  cols="30"
                  class="form-control"
                  placeholder="Address ..."
                  v-model="form.jobRes"
                ></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Last Salary</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder=""
                  v-model="form.lastSalary"
                />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Continuing</label>
                <input
                  type="date"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.continuing"
                />
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-success m-3 p-2"><i class="fa-solid fa-check"></i> | {{ updateinfo }}</button>
          <button type="reset" class="btn btn-danger m-3 p-2" @click.stop="closeModal"><i class="fa-solid fa-x"></i> | Cancel</button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
#BrandModal,
.modal-mask {
  position: absolute;
  overflow: scroll;
  z-index: 9998;
  top: 10px;
  left: auto;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-container {
  width: 300px;
  margin: 150px auto;
  padding: 20px 30px;
  background-color: #620d0d;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
}
</style>
