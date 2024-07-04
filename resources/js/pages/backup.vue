<template>
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
    <div>
        <button class="custom-btn btn-15 mb-3" @click="saveData">
            Save Data
        </button>
        <hot-table ref="hotTableComponent" :settings="hotSettings"></hot-table>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { HotTable } from "@handsontable/vue3";
import { registerAllModules } from "handsontable/registry";
import "handsontable/dist/handsontable.full.css";

// Register Handsontable's modules
registerAllModules();

const daysInMonth = ref([]);
const selectedMonth = ref();
const employee = ref([]);
const hotTableComponent = ref(null);

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
        "Employee ID",
        "Employee Name",
        "Department",
        ...daysInMonth.value.map(day => `${day}`),
    ];
    hotSettings.value.columns = [
        { type: "text", data: "Employee_Id", readOnly: true },
        { type: "text", data: "Employee_Name", readOnly: true },
        { type: "text", data: "Department", readOnly: true },
        // ...daysInMonth.value.map(() => ({ type: "dropdown", source: ["P", "A"], data: "" })),
        ...daysInMonth.value.map((day, index) => ({
            type: "dropdown",
            source: ["P", "A", "S", "C", "D", "H"],
            data: row => row.attendance[day - 1],
            editor: 'dropdown',
        })),
    ];
    getData();
    // Reload the data to apply the new settings
    hotTableComponent.value.hotInstance.updateSettings(hotSettings.value);
};

const hotSettings = ref({
    data: [],
    height: "auto",
    autoWrapRow: true,
    autoWrapCol: true,
    licenseKey: "non-commercial-and-evaluation",
    startRows: 3,
    startCols: 3,
    rowHeaders: true,
    colHeaders: true,
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
    columns: [
        { type: "autocomplete", data: "id", className: "", readOnly: true },
        {
            type: "autocomplete",
            data: "Employee_Id",
            className: "",
            readOnly: true,
        },
        {
            type: "autocomplete",
            data: "Full_Name",
            className: "",
            readOnly: false,
        },
        {
            type: "autocomplete",
            data: "Father_Name",
            className: "",
            readOnly: false,
        },
        {
            type: "autocomplete",
            data: "Mother_Name",
            className: "",
            readOnly: false,
        },
    ],
    colHeaders: [
        "Employee ID",
        "Employee Name",
        "Department",
    ],
    afterChange(changes, source) {
        if (source !== "loadData") {
            hotTableComponent.value.hotInstance.render();
        }
    },
});


// const getData = async () => {
//     const response = await axios.get("/api/employee/allemp");
//     employee.value = response.data;
//     if (employee.value) {
//         hotTableComponent.value.hotInstance.loadData(employee.value);
//     }
// };

const getData = async () => {
    const response = await axios.get("/api/employee/allemp");
    employee.value = response.data.map(emp => ({
        ...emp,
        attendance: Array.from({ length: daysInMonth.value.length }, () => ""),
    }));

    hotTableComponent.value.hotInstance.loadData(employee.value);
};

const saveData = async () => {
    const data = hotTableComponent.value.hotInstance.getData();
    try {
        const response = await axios.post("/api/employee", { data });
        if (response.data.success) {
            alert("Data saved successfully");
        } else {
            alert("Failed to save data");
        }
    } catch (error) {
        console.error("Error saving data:", error);
    }
};

// onMounted(() => getData());
</script>
