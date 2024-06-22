<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import academic from "../employee/component/academicComponent.vue";
import training from "../employee/component/trainingComponent.vue";
import work from "../employee/component/workComponent.vue";
import { useStore } from "vuex";
const route = useRoute();

const store = useStore();

const isLoading = ref(true);
const error = ref(null);
const selectedStore = ref(null);
const heading = ref(null);
const eid = store.state.employeeId || parseInt(route.params.id);
const empId = parseInt(route.params.id);

const academics = ref([]);
const trainings = ref([]);
const works = ref([]);

const academicModel = ref(false);
const trainingModel = ref(false);
const workModel = ref(false);

const academicOpened = (title) => {
    heading.value = title;
    academicModel.value = true;
};
const academicClose = () => {
    academicModel.value = false;
};

const trainingOpened = (title) => {
    heading.value = title;
    trainingModel.value = true;
};

const trainingClose = () => {
    trainingModel.value = false;
};

const workOpened = (title) => {
    heading.value = title;
    workModel.value = true;
};

const workClose = () => {
    workModel.value = false;
};

const getData = async () => {
    try {
        const [
        responseAcademic,
        responseTraining,
        responseWork,
        ] = await axios.all([
            axios.get(`/api/academic/${eid}`),
            axios.get(`/api/training/${eid}`),
            axios.get(`/api/work/${eid}`),
        ]);
        // const responseAcademic = await axios.get(`/api/academic/${eid}`);
        // const responseTraining = await axios.get(`/api/training/${eid}`);
        // const responseWork = await axios.get(`/api/work/${eid}`);

        academics.value = responseAcademic.data;
        trainings.value = responseTraining.data;
        works.value = responseWork.data;
    } catch (err) {
        error.value = err.message || "Error fetching data";
    } finally {
        isLoading.value = false;
    }
};

const editAcademmic = async (id) => {
    try {
        const response = await axios.get(`/api/academic/${id}/edit`);
        selectedStore.value = response.data;
        academicOpened("Update");
    } catch (err) {
        console.error("Error fetching store data for editing:", err);
    }
};

const editTraining = async (id) => {
    try {
        const response = await axios.get(`/api/training/${id}/edit`);
        selectedStore.value = response.data;
        trainingOpened("Update");
    } catch (err) {
        console.error("Error fetching store data for editing:", err);
    }
};

const submitHandler = async () => {
    await getData();
};
onMounted(() => getData());
</script>

<template>
    <academic
        :isOpen="academicModel"
        :editStore="selectedStore"
        :updateinfo="heading"
        :EID="eid"
        @modal-close="academicClose"
        @submit="submitHandler"
        name="first-modal"
    />

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-11">
                        <h4 class="card-title m-2">Academic Information</h4>
                    </div>
                    <div class="col-lg-1">
                        <button
                            @click="academicOpened('Save')"
                            type="button"
                            class="btn btn-success btn-icon"
                        >
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Level of Education</th>
                                <th>Degree</th>
                                <th>Institute</th>
                                <th>Board</th>
                                <th>Major Group</th>
                                <th>Scale</th>
                                <th>Result</th>
                                <th>Years Of Passing</th>
                                <th>Acheivement</th>
                                <th>Remarks</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="academic in academics"
                                :key="academic.id"
                            >
                                <td>
                                    {{
                                        academic.education.degree
                                            ? academic.education.degree.Name
                                            : "N/A"
                                    }}
                                </td>
                                <td>
                                    {{
                                        academic.education
                                            ? academic.education.Name
                                            : "N/A"
                                    }}
                                </td>
                                <td>{{ academic.Institute_Name }}</td>
                                <td>
                                    {{
                                        academic.board
                                            ? academic.board.Name
                                            : "N/A"
                                    }}
                                </td>
                                <td>{{ academic.Group }}</td>
                                <td>
                                    {{
                                        academic.scale
                                            ? academic.scale.Name
                                            : "N/A"
                                    }}
                                </td>
                                <td>{{ academic.Result }}</td>
                                <td>{{ academic.Result }}</td>
                                <td>{{ academic.Acheivement }}</td>
                                <td>{{ academic.Remarks }}</td>
                                <td>{{ academic.Institute_Name }}</td>
                                <td>
                                    <button
                                        class="btn btn-success"
                                        @click="editAcademmic(academic.id)"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <training
        :isOpen="trainingModel"
        :editStore="selectedStore"
        :updateinfo="heading"
        :EID="eid"
        @modal-close="trainingClose"
        @submit="submitHandler"
        name="first-modal"
    />

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-11">
                        <h4 class="card-title m-2">Training Information</h4>
                    </div>
                    <div class="col-lg-1">
                        <button
                            @click="trainingOpened('Save')"
                            type="button"
                            class="btn btn-success btn-icon"
                        >
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Training Infomation</th>
                                <th>Organized By</th>
                                <th>Topic Covered</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Remarks</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="training in trainings"
                                :key="training.id"
                            >
                                <td>{{ training.Training_Title }}</td>
                                <td>{{ training.Organized_By }}</td>
                                <td>{{ training.Topic_Covered }}</td>
                                <td>{{ training.From_Date }}</td>
                                <td>{{ training.To_Date }}</td>
                                <td>{{ training.Remarks }}</td>
                                <td>
                                    <button
                                        class="btn btn-success"
                                        @click="editTraining(training.id)"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <work
        :isOpen="workModel"
        :editStore="selectedStore"
        :updateinfo="heading"
        :EID="eid"
        @modal-close="workClose"
        @submit="submitHandler"
        name="first-modal"
    />

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-11">
                        <h4 class="card-title m-2">Work Experience</h4>
                    </div>
                    <div class="col-lg-1">
                        <button
                            @click="workOpened('Save')"
                            style="float: left"
                            type="button"
                            class="btn btn-success btn-icon"
                        >
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Company Business</th>
                                <th>Company Address</th>
                                <th>Designation</th>
                                <th>Job Nature</th>
                                <th>From Date</th>
                                <th>To Date</th>
                                <th>Job Responsibility</th>
                                <th>Last Salary</th>
                                <th>Continuing</th>
                                <th>Remarks</th>
                                <th>Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="work in works" :key="work.id">
                                <td>{{ work.Company_Name }}</td>
                                <td>{{ work.Company_Business }}</td>
                                <td>{{ work.Company_Address }}</td>
                                <td>{{ work.Designation }}</td>
                                <td>{{ work.Department }}</td>
                                <td>{{ work.From_Date }}</td>
                                <td>{{ work.To_Date }}</td>
                                <td>{{ work.Last_Salary }}</td>
                                <td>{{ work.Continuing }}</td>
                                <td>
                                    <button
                                        class="btn btn-success"
                                        @click="editHandler(academic.id)"
                                    >
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
