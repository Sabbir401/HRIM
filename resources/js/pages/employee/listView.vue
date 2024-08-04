<template>
    <div class="text-center">
        <h1>Artisan All Employee</h1>
    </div>
    <div>
        <div v-if="loading" class="overlay-loading">
            <div class="loader"></div>
        </div>
        <hot-table ref="hotTableComponent" :settings="hotSettings"></hot-table>
    </div>
    <div>
        <button @click="exportToExcel" class="custom-btn btn-13 mt-3">Export to Excel</button>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { HotTable } from "@handsontable/vue3";
import { registerAllModules } from "handsontable/registry";
import "handsontable/dist/handsontable.full.css";


// Register Handsontable's modules
registerAllModules();

const employee = ref([]);
const hotTableComponent = ref(null);
const loading = ref(true);

const hotSettings = ref({
    data: [],
    height: "auto",
    autoWrapRow: true,
    autoWrapCol: true,
    licenseKey: "non-commercial-and-evaluation",
    height: window.innerHeight - 200,

    fixedColumnsStart: 4,
    rowHeaders: true,
    colHeaders: ["ID", "Employee Name", "Department", "Designation", "Date of Birth", "Official Email", "Emergency Contact", "Blood Group", "Present Address", "Status"],
    columns: [
        { type: "text", data: "id", readOnly: true },
        { type: "text", data: "Full_Name", readOnly: true },
        { type: "text", data: "department", readOnly: true },
        { type: "text", data: "designation", readOnly: true },
        { type: "text", data: "DOB", readOnly: true },
        { type: "text", data: "Official_Email", readOnly: true },
        { type: "text", data: "Emergency_Contact", readOnly: true },
        { type: "text", data: "group", readOnly: true },
        { type: "text", data: "Present_Address", readOnly: true },
        { type: "text", data: "status", readOnly: true },
    ],
    hiddenColumns: {
        columns: [0],  
        indicators: false, 
    },
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
        const response = await axios.get("/api/employee-list");
        // employee.value = response.data;

        employee.value = response.data.map((emp) => ({
            ...emp,
            status: emp.status === "N" ? "Active" : "Inactive",
        }));

        hotSettings.value.data = employee.value;
        hotTableComponent.value.hotInstance.loadData(employee.value);
    } catch (error) {
        console.error("Error fetching data:", error);
    } finally {
        loading.value = false;
    }
};


function exportToExcel() {
    // Get the data from the Handsontable instance
    const data = hotTableComponent.value.hotInstance.getData();
    const headers = hotTableComponent.value.hotInstance.getColHeader();

    // Combine headers with data
    const sheetData = [headers, ...data];

    // Create a new workbook and a sheet
    const worksheet = XLSX.utils.aoa_to_sheet(sheetData);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

    // Create a binary string representation of the workbook
    const workbookBinary = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

    // Create a Blob from the binary data
    const blob = new Blob([workbookBinary], { type: 'application/octet-stream' });

    // Create a link element and trigger the download
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Employee_List.xlsx';
    link.click();

    // Clean up the URL object
    URL.revokeObjectURL(link.href);
}

onMounted(() => getData());
</script>
