
// // // Set today's date
// // document.getElementById("currentDate").innerText = new Date().toDateString();

// // // Logout action
// // function logout() {
// //   alert("Logging out...");
// //   // window.location.href = "../../main/index.php";
// //   // window.location.href = "../../main/index.php.php";
// // }
// document.addEventListener("DOMContentLoaded", function () {
//   const currentDateSpan = document.getElementById("currentDate");
//   if (currentDateSpan) {
//     const now = new Date();
//     const options = {
//       weekday: "short",
//       year: "numeric",
//       month: "short",
//       day: "numeric",
//     };
//     currentDateSpan.innerText = now.toLocaleDateString("en-GB", options); // Example: Wed, 2 Jul 2025
//   }
// });

// // Logout function (optional)
// function logout() {
//   alert("Logging out...");
//   // window.location.href = "../../main/index.php";
// }

// // --------- Helper Functions ---------
// function saveToLocalStorage(config) {
//   localStorage.setItem(config.name, JSON.stringify(config.list));
// }

// function loadFromLocalStorage(config) {
//   const saved = localStorage.getItem(config.name);
//   if (saved) {
//     try {
//       config.list = JSON.parse(saved);
//     } catch (e) {
//       config.list = [];
//     }
//   }
// }

// // --------- Render Functions ---------
// function renderList(config) {
//   const tableBody = document.getElementById(config.tableBodyId);
//   if (!tableBody) return;

//   // Search Filter
//   let items = config.list;
//   if (config.searchInputId && config.filterFn) {
//     const keyword =
//       document
//         .getElementById(config.searchInputId)
//         ?.value.trim()
//         .toLowerCase() || "";
//     if (keyword) {
//       items = items.filter((item) => config.filterFn(item, keyword));
//     }
//   }

//   // Pagination
//   const page = config.currentPage || 1;
//   const perPage = config.itemsPerPage || items.length;
//   const startIndex = (page - 1) * perPage;
//   const endIndex = startIndex + perPage;
//   const paginatedItems = items.slice(startIndex, endIndex);

//   // Render Table
//   tableBody.innerHTML = "";
//   if (paginatedItems.length === 0) {
//     tableBody.innerHTML = `
//       <tr>
//         <td colspan="${
//           config.exportFields.length || 6
//         }" class="text-center text-muted">No ${config.name} found.</td>
//       </tr>`;
//   } else {
//     paginatedItems.forEach((item, index) => {
//       const globalIndex = startIndex + index;
//       tableBody.innerHTML += config.renderRow(item, globalIndex);
//     });
//   }

//   renderPagination(config, items.length);
//   updateBadgeCount(config, items.length);
// }

// function renderPagination(config, totalItems) {
//   const container = document.getElementById(config.paginationId);
//   if (!container) return;

//   const totalPages = Math.ceil(totalItems / config.itemsPerPage);
//   config.totalPages = totalPages;

//   container.innerHTML = "";
//   if (totalPages <= 1) return;

//   // Prev Button
//   container.innerHTML += `
//     <button class="btn btn-outline-secondary mx-1" ${
//       config.currentPage === 1 ? "disabled" : ""
//     }
//       onclick="changePage('${config.name}', ${config.currentPage - 1})">
//       <i class="fas fa-angle-left"></i>
//     </button>
//   `;

//   // Page Numbers
//   for (let i = 1; i <= totalPages; i++) {
//     container.innerHTML += `
//       <button class="btn mx-1 ${
//         i === config.currentPage ? "btn-primary" : "btn-outline-primary"
//       }"
//         onclick="changePage('${config.name}', ${i})">${i}</button>
//     `;
//   }

//   // Next Button
//   container.innerHTML += `
//     <button class="btn btn-outline-secondary mx-1" ${
//       config.currentPage === totalPages ? "disabled" : ""
//     }
//       onclick="changePage('${config.name}', ${config.currentPage + 1})">
//       <i class="fas fa-angle-right"></i>
//     </button>
//   `;
// }

// function changePage(moduleName, newPage) {
//   const config = moduleConfigs[moduleName];
//   if (!config) return;
//   config.currentPage = newPage;
//   renderList(config);
// }

// function updateBadgeCount(config, count) {
//   if (!config.badgeId) return;
//   const badge = document.getElementById(config.badgeId);
//   if (badge) badge.textContent = count;
// }

// // --------- CRUD Functions ---------
// function saveItem(config) {
//   const item = {};
//   const indexField = document.getElementById(config.indexField);
//   const index = indexField.value;

//   for (const field of config.fields) {
//     const elem = document.getElementById(field);
//     if (!elem) continue;
//     let value = elem.value.trim();
//     if (elem.type === "number") value = parseFloat(value);
//     item[getFieldKey(field)] = value;
//   }

//   if (Object.values(item).some((val) => !val && val !== 0)) {
//     alert("Please fill all fields.");
//     return;
//   }

//   if (index === "") {
//     config.list.push(item);
//   } else {
//     config.list[parseInt(index)] = item;
//   }

//   if (config.useLocalStorage) saveToLocalStorage(config);
//   resetFormFields(config);
//   renderList(config);
//   closeModal(config.modalId);
// }

// function deleteItem(config, index) {
//   if (
//     !confirm(
//       `Are you sure you want to delete this ${
//         config.singularName || config.name
//       }?`
//     )
//   )
//     return;
//   config.list.splice(index, 1);
//   if (config.useLocalStorage) saveToLocalStorage(config);
//   renderList(config);
// }

// function editItem(config, index) {
//   const item = config.list[index];
//   if (!item) return;

//   config.fields.forEach((fieldId) => {
//     const input = document.getElementById(fieldId);
//     if (!input) return;
//     const key = getFieldKey(fieldId);
//     input.value = item[key] ?? "";
//   });

//   document.getElementById(config.indexField).value = index;
//   document.getElementById(config.modalTitleId).innerText =
//     config.modalTitleEdit;
//   openModal(config.modalId);
// }

// // --------- Form & Modal Helpers ---------
// function getFieldKey(fieldId) {
//   return fieldId
//     .replace(/^(new|edit)?(Service|Staff|Branch|Product)?/i, "")
//     .replace(/^[A-Z]/, (c) => c.toLowerCase());
// }

// function resetFormFields(config) {
//   for (const field of config.fields) {
//     const elem = document.getElementById(field);
//     if (!elem) continue;
//     if (elem.tagName === "SELECT") {
//       elem.selectedIndex = 0;
//     } else {
//       elem.value = "";
//     }
//   }
//   document.getElementById(config.indexField).value = "";
//   document.getElementById(config.modalTitleId).innerText = config.modalTitleAdd;
// }

// function openModal(modalId) {
//   const modal = new bootstrap.Modal(document.getElementById(modalId));
//   modal.show();
// }

// function closeModal(modalId) {
//   const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
//   modal?.hide();
// }

// // --------- Search ---------
// function setupSearch(config) {
//   if (!config.searchInputId || !config.filterFn) return;
//   const input = document.getElementById(config.searchInputId);
//   if (!input) return;
//   input.addEventListener("input", () => {
//     config.currentPage = 1;
//     renderList(config);
//   });
// }

// // --------- Export ---------
// function exportToCSV(config) {
//   if (!config.list || config.list.length === 0) {
//     alert("No data to export!");
//     return;
//   }
//   const csvRows = [];
//   const headers = config.exportFields;
//   csvRows.push(headers.join(","));
//   config.list.forEach((item) => {
//     const row = headers.map((key) => `"${item[key]}"`).join(",");
//     csvRows.push(row);
//   });
//   const csvContent = csvRows.join("\n");
//   const blob = new Blob([csvContent], { type: "text/csv" });
//   const url = URL.createObjectURL(blob);
//   const a = document.createElement("a");
//   a.href = url;
//   a.download = `${config.name}_data.csv`;
//   a.click();
//   URL.revokeObjectURL(url);
// }

// function exportToPDF(config) {
//   if (!config.list.length) return alert("No data to export.");
//   const doc = new jspdf.jsPDF();
//   const headers = [config.exportFields];
//   const data = config.list.map((item) =>
//     config.exportFields.map((k) => item[k])
//   );
//   doc.text(`${config.name.toUpperCase()} Report`, 14, 15);
//   doc.autoTable({
//     startY: 20,
//     head: headers,
//     body: data,
//     styles: { fontSize: 8 },
//   });
//   doc.save(`${config.name}_data.pdf`);
// }

// // --------- Module Configs ---------
// const moduleConfigs = {
//   staff: {
//     name: "staff",
//     singularName: "staff member",
//     list: [
//       {
//         name: "Ravi Sharma",
//         role: "Stylist",
//         phone: "9876543210",
//         email: "ravi@example.com",
//       },
//       {
//         name: "Meena Patel",
//         role: "Beautician",
//         phone: "9988776655",
//         email: "meena@example.com",
//       },
//     ],
//     fields: ["staffName", "staffRole", "staffPhone", "staffEmail"],
//     exportFields: ["name", "role", "phone", "email"],
//     indexField: "editStaffIndex",
//     modalId: "addStaffModal",
//     modalTitleId: "staffModalTitle",
//     modalTitleAdd: "Add Staff",
//     modalTitleEdit: "Edit Staff",
//     tableBodyId: "staffTableBody",
//     paginationId: "staffPagination",
//     itemsPerPage: 10,
//     searchInputId: "staffSearchInput",
//     useLocalStorage: true,
//     filterFn: (item, keyword) =>
//       item.name.toLowerCase().includes(keyword) ||
//       item.role.toLowerCase().includes(keyword) ||
//       item.email.toLowerCase().includes(keyword),
//     renderRow: (staff, index) => `
//       <tr>
//         <td>${index + 1}</td>
//         <td>${staff.name}</td>
//         <td>${staff.role}</td>
//         <td>${staff.phone}</td>
//         <td>${staff.email}</td>
//         <td>
//           <button class="btn btn-sm btn-warning me-1" onclick="editItem(moduleConfigs.staff, ${index})">Edit</button>
//           <button class="btn btn-sm btn-danger" onclick="deleteItem(moduleConfigs.staff, ${index})">Delete</button>
//         </td>
//       </tr>
//     `,
//   },
//   product: {
//     name: "product",
//     singularName: "product",
//     list: [
//       { name: "Shampoo", category: "Hair Care", price: 250, stock: 40 },
//       { name: "Facial Cream", category: "Skin Care", price: 500, stock: 25 },
//     ],
//     fields: ["productName", "productCategory", "productPrice", "productStock"],
//     exportFields: ["name", "category", "price", "stock"],
//     indexField: "editProductIndex",
//     modalId: "addProductModal",
//     modalTitleId: "productModalTitle",
//     modalTitleAdd: "Add Product",
//     modalTitleEdit: "Edit Product",
//     tableBodyId: "productTableBody",
//     paginationId: "productPagination",
//     itemsPerPage: 10,
//     searchInputId: "productSearchInput",
//     useLocalStorage: true,
//     filterFn: (item, keyword) =>
//       item.name.toLowerCase().includes(keyword) ||
//       item.category.toLowerCase().includes(keyword),
//     renderRow: (product, index) => {
//       const isLow = product.stock <= 10;
//       return `
//         <tr class="${isLow ? "table-danger" : ""}">
//           <td>${index + 1}</td>
//           <td>${product.name}</td>
//           <td>${product.category}</td>
//           <td>$${product.price}</td>
//           <td>${product.stock}</td>
//           <td>
//             <button class="btn btn-sm btn-warning me-1" onclick="editItem(moduleConfigs.product, ${index})">Edit</button>
//             <button class="btn btn-sm btn-danger" onclick="deleteItem(moduleConfigs.product, ${index})">Delete</button>
//           </td>
//         </tr>`;
//     },
//   },
//   branch: {
//     name: "branch",
//     singularName: "branch",
//     list: [
//       {
//         name: "Central Salon",
//         city: "Mumbai",
//         state: "Maharashtra",
//         postalCode: "400001",
//         latitude: 18.9388,
//         longitude: 72.8356,
//         chairs: 10,
//       },
//       {
//         name: "East End Salon",
//         city: "Kolkata",
//         state: "West Bengal",
//         postalCode: "700001",
//         latitude: 22.5726,
//         longitude: 88.3639,
//         chairs: 8,
//       },
//     ],
//     fields: [
//       "branchName",
//       "branchCity",
//       "branchState",
//       "branchPostalCode",
//       "branchLatitude",
//       "branchLongitude",
//       "branchChairs",
//     ],
//     exportFields: [
//       "name",
//       "city",
//       "state",
//       "postalCode",
//       "latitude",
//       "longitude",
//       "chairs",
//     ],
//     indexField: "editBranchIndex",
//     modalId: "branchModal",
//     modalTitleId: "branchModalTitle",
//     modalTitleAdd: "Add Branch",
//     modalTitleEdit: "Edit Branch",
//     tableBodyId: "branchTableBody",
//     paginationId: "branchPagination",
//     itemsPerPage: 10,
//     searchInputId: "branchSearchInput",
//     useLocalStorage: true,
//     filterFn: (item, keyword) =>
//       item.name.toLowerCase().includes(keyword) ||
//       item.state.toLowerCase().includes(keyword),
//     renderRow: (branch, index) => `
//       <tr>
//         <td>${index + 1}</td>
//         <td>${branch.name}</td>
//         <td>${branch.city}</td>
//         <td>${branch.state}</td>
//         <td>${branch.postalCode}</td>
//         <td>${branch.latitude}</td>
//         <td>${branch.longitude}</td>
//         <td>${branch.chairs}</td>
//         <td>
//           <button class="btn btn-sm btn-warning me-1" onclick="editItem(moduleConfigs.branch, ${index})">Edit</button>
//           <button class="btn btn-sm btn-danger" onclick="deleteItem(moduleConfigs.branch, ${index})">Delete</button>
//         </td>
//       </tr>
//     `,
//   },
//   service: {
//     name: "service",
//     singularName: "service",
//     list: [
//       {
//         name: "Women’s Haircut",
//         gender: "Female",
//         duration: "1 hr",
//         price: 50,
//         category: "Haircut",
//       },
//       {
//         name: "Men’s Haircut",
//         gender: "Male",
//         duration: "30 min",
//         price: 30,
//         category: "Haircut",
//       },
//       {
//         name: "Basic Facial",
//         gender: "Unisex",
//         duration: "30 min",
//         price: 40,
//         category: "Facial",
//       },
//     ],
//     fields: [
//       "newServiceName",
//       "newServiceGender",
//       "newServiceDuration",
//       "newServicePrice",
//       "newServiceCategory",
//     ],
//     exportFields: ["name", "gender", "duration", "price", "category"],
//     indexField: "editIndex",
//     modalId: "addServiceModal",
//     modalTitleId: "modalTitle",
//     modalTitleAdd: "Add Service",
//     modalTitleEdit: "Edit Service",
//     tableBodyId: "serviceTableBody",
//     paginationId: "pagination",
//     itemsPerPage: 9,
//     searchInputId: "serviceSearchInput",
//     useLocalStorage: true,
//     filterFn: (item, keyword) =>
//       item.name.toLowerCase().includes(keyword) ||
//       item.category.toLowerCase().includes(keyword),
//     renderRow: (service, index) => `
//       <tr>
//         <td>${index + 1}</td>
//         <td>${service.name}</td>
//         <td>${service.gender}</td>
//         <td>${service.duration}</td>
//         <td>$${service.price}</td>
//         <td>${service.category}</td>
//         <td>
//           <button class="btn btn-sm btn-warning me-1" onclick="editItem(moduleConfigs.service, ${index})">Edit</button>
//           <button class="btn btn-sm btn-danger" onclick="deleteItem(moduleConfigs.service, ${index})">Delete</button>
//         </td>
//       </tr>
//     `,
//   },
// };

// // --------- Initialize All Modules on Page Load ---------
// document.addEventListener("DOMContentLoaded", () => {
//   Object.values(moduleConfigs).forEach((config) => {
//     config.currentPage = 1;
//     if (config.useLocalStorage) loadFromLocalStorage(config);
//     renderList(config);
//     setupSearch(config);
//   });
// });
