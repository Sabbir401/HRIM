<script setup>
import {
  ref,
  reactive,
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
const educations = ref([]);
const boards = ref([]);
const scales = ref([]);
const degree = ref([]);

const form = ref({
  eid: EID,
  levelofEduId: null,
  degreeId: "",
  institute: "",
  boardId: "",
  major: "",
  scaleId: "",
  result: "",
  yop: "",
  acheivement: "",
  remarks: "",
});

const instance = getCurrentInstance();

const getData = async () => {
  try {
    const responseEducation = await axios.get("/api/education");
    const responseBoard = await axios.get("/api/board");
    const responseScale = await axios.get("/api/scale");

    educations.value = responseEducation.data;
    boards.value = responseBoard.data;
    scales.value = responseScale.data;
    if (updateinfo === "Update") {
      console.log("edit");
      form.value = {
        levelofEduId: editStore.education.education_id,
        degreeId: editStore.education.id,
        institute: editStore.Institute_Name,
        boardId: editStore.Board_Id,
        major: editStore.Group,
        scaleId: editStore.Scale_Id,
        result: editStore.Result,
        yop: editStore.Year_of_Passing,
        acheivement: editStore.Acheivement,
        remarks: editStore.Remarks,
      };
    }
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

const getDegree = async (id) => {
  try {
    const response = await axios.get(`/api/education/${id}`);
    degree.value = response.data;
  } catch (error) {
    console.error("Error updating store:", error);
    // Handle the error, e.g., display an error message
  }
};

const resetForm = () => {
  Object.keys(form).forEach((key) => {
    form[key] = "";
  });
};

const create = async () => {
  try {
    const response = await axios.post("/api/academic", form.value);
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
    class="modal-mask col-lg-6 col-md-6 grid-margin stretch-card"
    ref="target"
  >
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Academic Information</h4>
        <form class="forms-sample" @submit.prevent="create">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Level of Education</label>
                <select
                  class="form-control"
                  name="status"
                  id=""
                  v-model="form.levelofEduId"
                  @change="getDegree(form.levelofEduId)"
                >
                  <option selected disabled>select</option>
                  <option
                    v-for="edu in educations.education"
                    :key="edu.id"
                    :value="edu.id"
                  >
                    {{ edu.Name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Exam/Degree Title</label>
                <select
                  class="form-control"
                  name="status"
                  id=""
                  v-model="form.degreeId"
                >
                  <option selected disabled>select</option>
                  <option v-for="deg in degree" :key="deg.id" :value="deg.id">
                    {{ deg.Name }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Institute</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.institute"
                />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Board</label>
                <select
                  class="form-control"
                  name="status"
                  id=""
                  v-model="form.boardId"
                >
                  <option selected disabled>select</option>
                  <option
                    v-for="board in boards"
                    :key="board.id"
                    :value="board.id"
                  >
                    {{ board.Name }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Major Group</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.major"
                />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Scale</label>
                <select
                  class="form-control"
                  name="status"
                  id=""
                  v-model="form.scaleId"
                >
                  <option selected disabled>select</option>
                  <option
                    v-for="scale in scales"
                    :key="scale.id"
                    :value="scale.id"
                  >
                    {{ scale.Name }}
                  </option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Result</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.result"
                />
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Years of Passing</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Address"
                  v-model="form.yop"
                />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Acheivement</label>
                <textarea
                  name=""
                  id=""
                  cols="30"
                  rows="3"
                  class="form-control"
                  placeholder="Address ..."
                  v-model="form.acheivement"
                ></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Remarks</label>
                <textarea
                  name=""
                  id=""
                  cols="30"
                  rows="3"
                  class="form-control"
                  placeholder="Address ..."
                  v-model="form.remarks"
                ></textarea>
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
  z-index: 9998;
  top: 50px;
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
