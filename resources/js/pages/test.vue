<template>
    <div>
        <button class="custom-btn btn-15 mb-3" @click="saveData">Save Data</button>
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

const employee = ref([]);
const colHeaders = [
    "ID",
    "Company ID",
    "Employee ID",
    "Card No",
    "Full Name",
    "Father Name",
    "Mother Name",
    "Spouse Name",
    "Marital Status",
    "DOB",
    "Place of Birth",
    "Present Address",
    "Permanent Address",
    "Contact No",
    "Emergency Contact",
    "Gender",
    "Personal Email",
    "Official Email",
    "Blood Group ID",
    "Religion ID",
    "Nationality",
    "NID",
];

const hotSettings = ref({
    data: [],
    height: "auto",
    autoWrapRow: true,
    autoWrapCol: true,
    licenseKey: "non-commercial-and-evaluation",
    height: window.innerHeight - 150,
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
    hiddenColumns: {
        columns: [1, 0],
        indicators: true,
    },
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
        "Company ID",
        "Employee ID",
        "Card No",
        "Full Name",
        "Father Name",
        "Mother Name",
        "Spouse Name",
        "Marital Status",
        "DOB",
        "Place of Birth",
        "Present Address",
        "Permanent Address",
        "Contact No",
        "Emergency Contact",
        "Gender",
        "Personal Email",
        "Official Email",
        "Blood Group ID",
        "Religion ID",
        "Nationality",
        "NID",
    ],
    afterChange(changes, source) {
        if (source !== 'loadData') {
            hotTableComponent.value.hotInstance.render();
        }
    },
});

const hotTableComponent = ref(null);

const getData = async () => {
    const response = await axios.get("/api/employee/allemp");
    employee.value = response.data;
    if(employee.value){
        hotTableComponent.value.hotInstance.loadData(employee.value);
    }
};

const saveData = async () => {
    const data = hotTableComponent.value.hotInstance.getData();
    try {
        const response = await axios.post("/api/employee", { data });
        if (response.data.success) {
            alert('Data saved successfully');
        } else {
            alert('Failed to save data');
        }
    } catch (error) {
        console.error("Error saving data:", error);
    }
};

onMounted(() => getData());
</script>

