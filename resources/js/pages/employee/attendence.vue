<template>
    <div>
        <div class="row d-flex justify-content-between">
            <div class="col-lg-2 mb-3">
                <label for="">Select a Month</label>
                <select
                    class="form-control"
                    name="status"
                    v-model="selectedMonth"
                    @change="generateDays"
                >
                    <option
                        v-for="(month, index) in months"
                        :key="month"
                        :value="index"
                    >
                        {{ month }}
                    </option>
                </select>
            </div>
            <div class="col-lg-2 mt-5">
                <button class="custom-btn btn-13 p-2" @click="fetchAttendence">
                    Fetch Attendence
                </button>
            </div>
        </div>
        <div>
            <button class="custom-btn btn-15 mb-3" @click="saveData">
                Save Data
            </button>
            <div v-if="loading" class="overlay-loading">
                <div class="loader"></div>
            </div>
            <hot-table
                ref="hotTableComponent"
                :settings="hotSettings"
            ></hot-table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { HotTable } from "@handsontable/vue3";
import { registerAllModules } from "handsontable/registry";
import "handsontable/dist/handsontable.full.css";
import Swal from "sweetalert2";

// Register Handsontable's modules
registerAllModules();

const daysInMonth = ref([]);
const selectedMonth = ref(new Date().getMonth());
const employee = ref([]);
const hotTableComponent = ref(null);
const loading = ref(true);
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

const generateDays = () => {
    const year = new Date().getFullYear();
    const month = selectedMonth.value;
    const days = new Date(year, month + 1, 0).getDate();

    daysInMonth.value = Array.from({ length: days }, (v, k) => k + 1);

    hotSettings.value.colHeaders = [
        "ID",
        "Department",
        "Employee Name",
        "Designation",
        ...daysInMonth.value.map((day) => `${day}`),
    ];

    hotSettings.value.columns = [
        { type: "text", data: "id", readOnly: true },
        { type: "text", data: "department", readOnly: true },
        { type: "text", data: "Full_Name", readOnly: true },
        { type: "text", data: "designation", readOnly: true },
        ...daysInMonth.value.map((day, index) => ({
            type: "dropdown",
            source: ["", "P", "A", "L"],
            data: `attendance.${day}`,
            editor: "dropdown",
        })),
    ];

    hotTableComponent.value.hotInstance.updateSettings(hotSettings.value);
    getData();
};

const hotSettings = ref({
    data: [],
    height: "auto",
    autoWrapRow: true,
    autoWrapCol: true,
    licenseKey: "non-commercial-and-evaluation",
    height: window.innerHeight - 200,

    fixedColumnsStart: 4,
    rowHeaders: true,
    colHeaders: ["Department", "Employee Name", "Designation"],
    columns: [
        { type: "text", data: "id", readOnly: true },
        { type: "text", data: "department", readOnly: true },
        { type: "text", data: "Full_Name", readOnly: true },
        { type: "text", data: "designation", readOnly: true },
    ],
    minSpareRows: 1,
    minSpareCols: 1,
    manualColumnResize: true,
    useFormula: true,
    filters: true,
    draw: true,
    stretchH: "all",
    customBorders: true,
    columnSorting: true,
    sortIndicator: true,
    dropdownMenu: [
        "make_read_only",
        "alignment",
        "---------",
        "filter_by_condition",
        "filter_by_value",
        "filter_action_bar",
    ],
    contextMenu: [
        "undo",
        "redo",
        "---------",
        "make_read_only",
        "alignment",
        "borders",
        "remove_row",
    ],
    afterChange(changes, source) {
        if (source !== "loadData") {
            hotTableComponent.value.hotInstance.render();
        }
    },
});

const getData = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/emp-attendence");
        const responseData = await axios.get("/api/attendence", {
            params: { month: selectedMonth.value },
        });

        employee.value = response.data.map((emp) => {
            const attendance = {};
            daysInMonth.value.forEach((day) => {
                const attendanceRecord = responseData.data.find((record) => {
                    const recordDate = new Date(record.Date);
                    const recordDay = recordDate.getDate();
                    const isMatching =
                        record.EID === emp.id && recordDay === day;
                    return isMatching;
                });
                attendance[day] = attendanceRecord
                    ? attendanceRecord.Status
                    : "";
            });

            return {
                ...emp,
                attendance,
            };
        });

        hotSettings.value.data = employee.value;
        hotTableComponent.value.hotInstance.loadData(employee.value);
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
    }
};

const fetchAttendence = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/fetch-attendence");
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
    }
};

const saveData = async () => {
    const data = hotTableComponent.value.hotInstance.getData();
    const attendanceData = data.map((row) => {
        const attendance = row.slice(4); // only attendance status values
        return {
            id: row[0],
            attendance: attendance,
        };
    });

    try {
        const result = await Swal.fire({
            title: "Are you sure?",
            text: "Do you want to submit this form?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, submit it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true,
        });

        if (result.isConfirmed) {
            const response = await axios.post("/api/attendence", {
                attendanceData,
                month: selectedMonth.value,
            });
            Swal.fire({
                    title: "Success!",
                    text: "Attendence Updated",
                    icon: "success",
                    confirmButtonText: "OK"
                });
        }
    } catch (error) {
        console.error("Error saving data:", error);
    }
};

onMounted(() => generateDays());
</script>
