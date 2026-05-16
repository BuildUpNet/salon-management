// function loadListFromStorage(config) {
//   if (!config.useLocalStorage) return;

//   const saved = localStorage.getItem(config.name + "List");
//   if (saved && saved !== "[]") {
//     try {
//       config.list = JSON.parse(saved);
//     } catch (e) {
//       console.error("Invalid JSON in localStorage for", config.name);
//     }
//   }
// }

// // -------------------- SET CURRENT DATE --------------------
// document.addEventListener("DOMContentLoaded", () => {
//   const dateElem = document.getElementById("currentDate");
//   if (dateElem) {
//     dateElem.innerText = new Date().toDateString();
//   }

//   // Initial renders
//   renderStaffList();
//   renderBranchList();
//   renderProductList();
// });

// // -------------------- LOGOUT FUNCTION --------------------
// function logout() {
//   if (confirm("Are you sure you want to logout?")) {
//     alert("Logging out...");
//     // window.location.href = "../../main/index.php";
//   }
// }

// // function renderListWithPagination(config) {
// //   const tbody = document.getElementById(config.tableBodyId);
// //   if (!tbody) return;

// //   let filteredList = config.list;

// //   //  Search Filter
// //   if (config.searchInputId && config.filterFn) {
// //     const keyword = document
// //       .getElementById(config.searchInputId)
// //       ?.value.trim()
// //       .toLowerCase();
// //     if (keyword) {
// //       filteredList = filteredList.filter((item) =>
// //         config.filterFn(item, keyword)
// //       );
// //     }
// //   }

// //   const startIndex = (config.currentPage - 1) * config.itemsPerPage;
// //   const endIndex = startIndex + config.itemsPerPage;
// //   const paginatedItems = filteredList.slice(startIndex, endIndex);

// //   tbody.innerHTML = "";
// //   paginatedItems.forEach((item, i) => {
// //     const globalIndex = startIndex + i;
// //     tbody.innerHTML += config.renderRow(item, globalIndex);
// //   });

// //   renderPagination(config, filteredList.length);
// // }
// // function renderListWithPagination(config) {
// //   const tbody = document.getElementById(config.tableBodyId);
// //   if (!tbody) return;

// //   let filteredList = config.list;

// //   // Search Filter
// //   if (config.searchInputId && config.filterFn) {
// //     const keyword = document
// //       .getElementById(config.searchInputId)
// //       ?.value.trim()
// //       .toLowerCase();
// //     if (keyword) {
// //       filteredList = filteredList.filter((item) =>
// //         config.filterFn(item, keyword)
// //       );
// //     }
// //   }

// //   const startIndex = (config.currentPage - 1) * config.itemsPerPage;
// //   const endIndex = startIndex + config.itemsPerPage;
// //   const paginatedItems = filteredList.slice(startIndex, endIndex);

// //   tbody.innerHTML = "";
// //   paginatedItems.forEach((item, i) => {
// //     const globalIndex = startIndex + i;
// //     tbody.innerHTML += config.renderRow(item, globalIndex);
// //   });

// //   renderPagination(config, filteredList.length);
// // }

// function renderPagination(config, totalItems) {
//   const container = document.getElementById(config.paginationId);
//   if (!container || !config.paginationId) return;

//   const totalPages = Math.ceil(totalItems / config.itemsPerPage);
//   if (totalPages <= 1) {
//     container.innerHTML = "";
//     return;
//   }

//   container.innerHTML = `
//     <button class="btn btn-outline-secondary mx-1" 
//             ${config.currentPage === 1 ? "disabled" : ""}
//             onclick="changePage('${config.name}', ${config.currentPage - 1})">
//       <i class="fas fa-angle-left"></i>
//     </button>
//   `;

//   for (let i = 1; i <= totalPages; i++) {
//     container.innerHTML += `
//       <button class="btn mx-1 ${
//         i === config.currentPage ? "btn-primary" : "btn-outline-primary"
//       }" 
//               onclick="changePage('${config.name}', ${i})">${i}</button>
//     `;
//   }

//   container.innerHTML += `
//     <button class="btn btn-outline-secondary mx-1" 
//             ${config.currentPage === totalPages ? "disabled" : ""}
//             onclick="changePage('${config.name}', ${config.currentPage + 1})">
//       <i class="fas fa-angle-right"></i>
//     </button>
//   `;
// }
// function changePage(moduleName, newPage) {
//   const config = moduleConfigs[moduleName];
//   config.currentPage = newPage;
//   // renderListWithPagination(config);
// }

// //  Save item (Add/Edit) based on config.fields & index
// // function saveItem(config) {
// //   const index = document.getElementById(config.indexField)?.value;
// //   const item = {};

// //   for (const field of config.fields) {
// //     const input = document.getElementById(field);
// //     if (!input) continue;
// //     item[field.replace(config.name, "").toLowerCase()] = input.value.trim();
// //   }

// //   if (Object.values(item).some((val) => !val)) {
// //     alert("Please fill all fields.");
// //     return;
// //   }

// //   if (index === "") {
// //     config.list.push(item);
// //   } else {
// //     config.list[parseInt(index)] = item;
// //   }

// //   if (config.useLocalStorage) saveToLocal(config);
// //   resetForm(config);
// //   closeModal(config.modalId);
// //   renderListWithPagination(config);
// // }
// function saveItem(config) {
//   const index = document.getElementById(config.indexField)?.value;
//   const item = {};

//   for (const field of config.fields) {
//     const input = document.getElementById(field);
//     if (!input) continue;
//     item[field.replace(config.name, "").toLowerCase()] = input.value.trim();
//   }

//   if (Object.values(item).some((val) => !val)) {
//     alert("Please fill all fields.");
//     return;
//   }

//   if (index === "") {
//     config.list.push(item);
//   } else {
//     config.list[parseInt(index)] = item;
//   }

//   if (config.useLocalStorage) saveToLocal(config);
//   resetForm(config);
//   closeModal(config.modalId);
//   // renderListWithPagination(config);
// }

// function deleteItem(config, index) {
//   if (confirm("Are you sure you want to delete this item?")) {
//     config.list.splice(index, 1);
//     if (config.useLocalStorage) saveToLocal(config);
//     renderListWithPagination(config);
//   }
// }

// //  Edit item — fills form using config.fields
// function editItem(config, index) {
//   const item = config.list[index];

//   for (const field of config.fields) {
//     const input = document.getElementById(field);
//     if (!input) continue;

//     const key = field.replace(config.name, "").toLowerCase();
//     input.value = item[key] ?? "";
//   }

//   document.getElementById(config.indexField).value = index;
//   document.getElementById(config.modalTitleId).innerText =
//     config.modalTitleEdit;
//   openModal(config.modalId);
// }

// //  Delete item
// function deleteItem(config, index) {
//   if (confirm("Are you sure you want to delete this item?")) {
//     config.list.splice(index, 1);
//     if (config.useLocalStorage) saveToLocal(config);
//     renderListWithPagination(config);
//   }
// }

// //  Reset form fields
// function resetForm(config) {
//   for (const field of config.fields) {
//     const input = document.getElementById(field);
//     if (!input) continue;
//     input.value = "";
//   }

//   const indexInput = document.getElementById(config.indexField);
//   if (indexInput) indexInput.value = "";

//   const title = document.getElementById(config.modalTitleId);
//   if (title) title.innerText = config.modalTitleAdd;
// }
// //  Open Bootstrap Modal
// function openModal(modalId) {
//   const modal = new bootstrap.Modal(document.getElementById(modalId));
//   modal.show();
// }

// //  Close Bootstrap Modal
// function closeModal(modalId) {
//   const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
//   modal?.hide();
// }

// //  Attach search input listener
// function initSearchFilter(config) {
//   if (!config.searchInputId || !config.filterFn) return;
//   const input = document.getElementById(config.searchInputId);
//   if (!input) return;

//   input.addEventListener("input", () => {
//     config.currentPage = 1;
//     renderListWithPagination(config);
//   });
// }
// //  Export visible list to CSV
// function exportToCSV(config) {
//   const headers = config.exportHeaders;
//   const rows = config.list.map((item) =>
//     config.exportFields.map((field) => `"${item[field] ?? ""}"`)
//   );

//   let csv = headers.join(",") + "\n" + rows.map((r) => r.join(",")).join("\n");

//   const blob = new Blob([csv], { type: "text/csv" });
//   const url = URL.createObjectURL(blob);

//   const link = document.createElement("a");
//   link.href = url;
//   link.download = config.exportFilename + ".csv";
//   link.click();

//   URL.revokeObjectURL(url);
// }
// //  Check for low stock and mark rows
// function highlightLowStock(config) {
//   if (!config.lowStockThreshold || !config.tableBodyId) return;

//   const tbody = document.getElementById(config.tableBodyId);
//   if (!tbody) return;

//   Array.from(tbody.rows).forEach((row, index) => {
//     const stock = config.list[index]?.stock;
//     if (typeof stock !== "undefined" && stock <= config.lowStockThreshold) {
//       row.classList.add("table-danger");
//     }
//   });
// }
// // Save data to localStorage
// // function saveToLocal(config) {
// //   localStorage.setItem(config.name, JSON.stringify(config.list));
// // }

// // Load data from localStorage
// // function loadFromLocal(config) {
// //   const data = localStorage.getItem(config.name);
// //   if (data) {
// //     try {
// //       config.list = JSON.parse(data);
// //     } catch {
// //       config.list = [];
// //     }
// //   }
// // }

// function initializeModule(config) {
//   config.currentPage = 1;
//   if (config.useLocalStorage) loadFromLocal(config);
//   initSearchFilter(config);
//   renderListWithPagination(config);
// }
// // //  Save data to localStorage
// // function saveToLocal(config) {
// //   localStorage.setItem(`${config.name}List`, JSON.stringify(config.list));
// // }

// // //  Load data from localStorage
// // function loadFromLocal(config) {
// //   const data = localStorage.getItem(`${config.name}List`);
// //   if (data) {
// //     try {
// //       config.list = JSON.parse(data);
// //     } catch {
// //       config.list = [];
// //     }
// //   }
// // }
// // function initializeModule(config) {
// //   config.currentPage = 1;
// //   if (config.useLocalStorage) loadFromLocal(config);
// //   initSearchFilter(config);
// //   renderListWithPagination(config);
// // }

// // Staff Module Config
// const moduleConfigs = {
//   staff: {
//     name: "staff",
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

//     // 🔍 Filter Logic
//     filterFn: (item, keyword) =>
//       item.name.toLowerCase().includes(keyword) ||
//       item.role.toLowerCase().includes(keyword) ||
//       item.email.toLowerCase().includes(keyword),

//     // 🧾 Export
//     exportHeaders: ["Name", "Role", "Phone", "Email"],
//     exportFields: ["name", "role", "phone", "email"],
//     exportFilename: "Staff_List",

//     // 🧩 Render Row
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
// };

// //  Product Module Config
// moduleConfigs.product = {
//   name: "product",
//   list: [
//     { name: "Shampoo", category: "Hair Care", price: 250, stock: 40 },
//     { name: "Facial Cream", category: "Skin Care", price: 500, stock: 25 },
//   ],
//   fields: ["productName", "productCategory", "productPrice", "productStock"],
//   indexField: "editProductIndex",
//   modalId: "addProductModal",
//   modalTitleId: "productModalTitle",
//   modalTitleAdd: "Add Product",
//   modalTitleEdit: "Edit Product",
//   tableBodyId: "productTableBody",
//   paginationId: "productPagination",
//   itemsPerPage: 10,
//   searchInputId: "productSearchInput",
//   useLocalStorage: true,
//   lowStockThreshold: 10,

//   //   // 🔍 Filter Logic
//   filterFn: (item, keyword) =>
//     item.name.toLowerCase().includes(keyword) ||
//     item.category.toLowerCase().includes(keyword),

//   //   // 🧾 Export
//   exportHeaders: ["Name", "Category", "Price", "Stock"],
//   exportFields: ["name", "category", "price", "stock"],
//   exportFilename: "Product_List",

//   //   // 🧩 Render Row with stock warning
//   renderRow: (product, index) => {
//     const isLow = product.stock <= 10;
//     return `
//       <tr class="${isLow ? "table-danger" : ""}">
//         <td>${index + 1}</td>
//         <td>${product.name}</td>
//         <td>${product.category}</td>
//         <td>₹${product.price}</td>
//         <td>${product.stock}</td>
//         <td>
//           <button class="btn btn-sm btn-warning me-1" onclick="editItem(moduleConfigs.product, ${index})">Edit</button>
//           <button class="btn btn-sm btn-danger" onclick="deleteItem(moduleConfigs.product, ${index})">Delete</button>
//         </td>
//       </tr>
//     `;
//   },
// };

// // 🏢 Branch Module Config
// moduleConfigs.branch = {
//   name: "branch",
//   list: [
//     {
//       name: "Central Salon",
//       city: "Mumbai",
//       state: "Maharashtra",
//       postalCode: "400001",
//       latitude: 18.9388,
//       longitude: 72.8356,
//       chairs: 10,
//     },
//     {
//       name: "East End Salon",
//       city: "Kolkata",
//       state: "West Bengal",
//       postalCode: "700001",
//       latitude: 22.5726,
//       longitude: 88.3639,
//       chairs: 8,
//     },
//     {
//       name: "Southside Salon",
//       city: "Chennai",
//       state: "Tamil Nadu",
//       postalCode: "600001",
//       latitude: 13.0827,
//       longitude: 80.2707,
//       chairs: 12,
//     },
//   ],
//   fields: [
//     "branchName",
//     "branchCity",
//     "branchState",
//     "branchPostalCode",
//     "branchLatitude",
//     "branchLongitude",
//     "branchChairs",
//   ],
//   indexField: "editBranchIndex",
//   modalId: "branchModal",
//   modalTitleId: "branchModalTitle",
//   modalTitleAdd: "Add Branch",
//   modalTitleEdit: "Edit Branch",
//   tableBodyId: "branchTableBody",
//   paginationId: "branchPagination", //  Pagination added
//   itemsPerPage: 10,
//   searchInputId: "branchSearchInput",
//   useLocalStorage: true,

//   // 🔍 Filter
//   filterFn: (item, keyword) =>
//     item.name.toLowerCase().includes(keyword) ||
//     item.city.toLowerCase().includes(keyword) ||
//     item.state.toLowerCase().includes(keyword),

//   // 🧾 Export
//   exportHeaders: ["Name", "City", "State", "Postal", "Lat", "Long", "Chairs"],
//   exportFields: [
//     "name",
//     "city",
//     "state",
//     "postalCode",
//     "latitude",
//     "longitude",
//     "chairs",
//   ],
//   exportFilename: "Branch_List",

//   // 🧩 Row Rendering
//   renderRow: (branch, index) => `
//     <tr>
//       <td>${index + 1}</td>
//       <td>${branch.name}</td>
//       <td>${branch.city}</td>
//       <td>${branch.state}</td>
//       <td>${branch.postalCode}</td>
//       <td>${branch.latitude}</td>
//       <td>${branch.longitude}</td>
//       <td>${branch.chairs}</td>
//       <td>
//         <button class="btn btn-sm btn-warning me-1" onclick="editItem(moduleConfigs.branch, ${index})">Edit</button>
//         <button class="btn btn-sm btn-danger" onclick="deleteItem(moduleConfigs.branch, ${index})">Delete</button>
//       </td>
//     </tr>
//   `,
// };

// // 💇‍♀️ Service Module Config
// moduleConfigs.service = {
//   name: "service",
//   list: [
//     {
//       name: "Women’s Haircut",
//       gender: "Female",
//       duration: "1 hr",
//       price: 50,
//       category: "Haircut",
//     },
//     {
//       name: "Men’s Haircut",
//       gender: "Male",
//       duration: "30 min",
//       price: 30,
//       category: "Haircut",
//     },
//     {
//       name: "Basic Facial",
//       gender: "Unisex",
//       duration: "30 min",
//       price: 40,
//       category: "Facial",
//     },
//     {
//       name: "Anti-Aging Facial",
//       gender: "Female",
//       duration: "45 min",
//       price: 60,
//       category: "Facial",
//     },
//     {
//       name: "Swedish Massage",
//       gender: "Unisex",
//       duration: "1 hr",
//       price: 70,
//       category: "Massage",
//     },
//     {
//       name: "Full Body Spa",
//       gender: "Unisex",
//       duration: "1.5 hr",
//       price: 90,
//       category: "Spa",
//     },
//   ],
//   fields: [
//     "newServiceName",
//     "newServiceGender",
//     "newServiceDuration",
//     "newServicePrice",
//     "newServiceCategory",
//   ],
//   indexField: "editIndex",
//   modalId: "addServiceModal",
//   modalTitleId: "modalTitle",
//   modalTitleAdd: "Add Service",
//   modalTitleEdit: "Edit Service",
//   tableBodyId: "serviceTableBody",
//   paginationId: "pagination",
//   itemsPerPage: 9,
//   searchInputId: "serviceSearchInput",
//   useLocalStorage: true,

//   // 🔍 Filter Logic
//   filterFn: (item, keyword) =>
//     item.name.toLowerCase().includes(keyword) ||
//     item.category.toLowerCase().includes(keyword) ||
//     item.gender.toLowerCase().includes(keyword),

//   // 🧾 Export
//   exportHeaders: ["Name", "Gender", "Duration", "Price", "Category"],
//   exportFields: ["name", "gender", "duration", "price", "category"],
//   exportFilename: "Service_List",

//   // 🧩 Render Row
//   renderRow: (service, index) => `
//     <tr>
//       <td>${index + 1}</td>
//       <td>${service.name}</td>
//       <td>${service.gender}</td>
//       <td>${service.duration}</td>
//       <td>$${service.price}</td>
//       <td>${service.category}</td>
//       <td>
//         <button class="btn btn-sm btn-warning me-1" onclick="editItem(moduleConfigs.service, ${index})">Edit</button>
//         <button class="btn btn-sm btn-danger" onclick="deleteItem(moduleConfigs.service, ${index})">Delete</button>
//       </td>
//     </tr>
//   `,
// };

// //  Initialize Module Based on Config
// function initializeModule(config) {
//   // Load from localStorage if enabled
//   if (config.useLocalStorage) {
//     const saved = localStorage.getItem(config.name);
//     if (saved) config.list = JSON.parse(saved);
//   }

//   // Initial Render
//   // renderPaginatedList(config);

//   // Setup Search
//   if (config.searchInputId) {
//     const searchInput = document.getElementById(config.searchInputId);
//     if (searchInput) {
//       searchInput.addEventListener("input", () => {
//         config.currentSearch = searchInput.value.trim().toLowerCase();
//         config.currentPage = 1;
//         renderPaginatedList(config);
//       });
//     }
//   }

//   // Export CSV Button (if exists)
//   const exportBtn = document.getElementById(`${config.name}ExportCSV`);
//   if (exportBtn) {
//     exportBtn.addEventListener("click", () => exportToCSV(config));
//   }

//   // Set page default
//   config.currentPage = 1;
// }

// // Render Table with Pagination & Search
// // function renderPaginatedList(config) {
// //   const tbody = document.getElementById(config.tableBodyId);
// //   if (!tbody) return;

// //   // 🔍 Apply Search Filter
// //   const keyword = config.currentSearch || "";
// //   const fullList = config.list;
// //   const filtered =
// //     keyword && config.filterFn
// //       ? fullList.filter((item) => config.filterFn(item, keyword))
// //       : fullList;

// //   // 🧮 Pagination Calculation
// //   const page = config.currentPage || 1;
// //   const perPage = config.itemsPerPage || filtered.length;
// //   const startIndex = (page - 1) * perPage;
// //   const endIndex = startIndex + perPage;
// //   const paginated = filtered.slice(startIndex, endIndex);

// //   // 🧩 Render Table Body
// //   tbody.innerHTML = "";
// //   paginated.forEach((item, i) => {
// //     const globalIndex = startIndex + i;
// //     tbody.innerHTML += config.renderRow(item, globalIndex);
// //   });

// //   // 📄 Render Pagination
// //   renderPaginationControls(config, filtered.length);
// // }

// // ⏩ Render Pagination Buttons Dynamically
// function renderPaginationControls(config, totalItems) {
//   if (!config.paginationId) return; // Pagination disabled
//   const container = document.getElementById(config.paginationId);
//   if (!container) return;

//   const totalPages = Math.ceil(totalItems / config.itemsPerPage);
//   config.totalPages = totalPages;

//   container.innerHTML = "";

//   if (totalPages <= 1) return;

//   // ⬅ Prev Button
//   container.innerHTML += `
//     <button class="btn btn-outline-secondary mx-1" ${
//       config.currentPage === 1 ? "disabled" : ""
//     } onclick="changePage('${config.name}', ${config.currentPage - 1})">
//       <i class="fas fa-angle-left"></i>
//     </button>
//   `;

//   // 🔢 Page Numbers
//   for (let i = 1; i <= totalPages; i++) {
//     container.innerHTML += `
//       <button class="btn mx-1 ${
//         i === config.currentPage ? "btn-primary" : "btn-outline-primary"
//       }" onclick="changePage('${config.name}', ${i})">${i}</button>
//     `;
//   }

//   // ➡ Next Button
//   container.innerHTML += `
//     <button class="btn btn-outline-secondary mx-1" ${
//       config.currentPage === totalPages ? "disabled" : ""
//     } onclick="changePage('${config.name}', ${config.currentPage + 1})">
//       <i class="fas fa-angle-right"></i>
//     </button>
//   `;
// }
// function changePage(moduleName, newPage) {
//   const config = moduleConfigs[moduleName];
//   config.currentPage = newPage;
//   renderPaginatedList(config);
// }

// // 💾 Save or Update Item in List
// function saveItem(config) {
//   const item = {};
//   const indexField = document.getElementById(config.indexField);
//   const index = indexField.value;

//   // 🔁 Loop through all field IDs to build the object
//   for (const field of config.fields) {
//     const elem = document.getElementById(field);
//     if (!elem) continue;

//     let value = elem.value.trim();
//     if (elem.type === "number") value = parseFloat(value);

//     item[getFieldKey(field)] = value;
//   }

//   // ✍️ Save or Update
//   if (index === "") {
//     config.list.push(item);
//   } else {
//     config.list[parseInt(index)] = item;
//   }

//   // 💽 Save to localStorage (if enabled)
//   if (config.useLocalStorage) {
//     localStorage.setItem(config.name, JSON.stringify(config.list));
//   }

//   // 🔁 Reset Form + Refresh Table + Close Modal
//   resetFormFields(config);
//   renderPaginatedList(config);
//   bootstrap.Modal.getInstance(document.getElementById(config.modalId)).hide();
// }

// // 🔍 Helper to extract object key from field ID (e.g. productName => name)
// function getFieldKey(fieldId) {
//   return fieldId
//     .replace(/^(new|edit)?(Service|Staff|Branch|Product)?/i, "")
//     .replace(/^[A-Z]/, (c) => c.toLowerCase());
// }
// // 🧹 Reset all Form Fields + Modal Title
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

// //  Populate Form to Edit an Item
// function editItem(config, index) {
//   const item = config.list[index];
//   if (!item) return;

//   config.fields.forEach((fieldId) => {
//     const input = document.getElementById(fieldId);
//     if (!input) return;

//     const key = getFieldKey(fieldId);
//     input.value = item[key];
//   });

//   // Set edit index
//   document.getElementById(config.indexField).value = index;

//   // Update modal title
//   document.getElementById(config.modalTitleId).innerText =
//     config.modalTitleEdit;

//   // Show modal
//   new bootstrap.Modal(document.getElementById(config.modalId)).show();
// }
// //  Delete Item with Confirmation
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

//   // Sync to localStorage if enabled
//   if (config.useLocalStorage) {
//     localStorage.setItem(config.name, JSON.stringify(config.list));
//   }

//   renderPaginatedList(config);
// }
// // singularName: "staff member";

// //  All Module Configurations
// const moduleConfigs = {
//   staff: {
//     name: "staff",
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
//           <td>₹${product.price}</td>
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

// // 🚀 Initialize All Modules
// Object.values(moduleConfigs).forEach((config) => {
//   initializeModule(config);
// });
// // 🧾 Export List to CSV
// function exportToCSV(config) {
//   if (!config.list || config.list.length === 0) {
//     alert("No data to export!");
//     return;
//   }

//   const csvRows = [];
//   const headers = Object.keys(config.list[0]);
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
// //  Highlight Product Row if Low Stock
// function productStockWarning(product) {
//   return product.stock !== undefined && product.stock < 5
//     ? ' class="table-danger"'
//     : "";
// }
// //  Load data from localStorage
// function loadFromLocalStorage(config) {
//   const saved = localStorage.getItem(config.name);
//   if (saved) {
//     try {
//       config.list = JSON.parse(saved);
//     } catch (e) {
//       console.error(`Failed to parse ${config.name} from localStorage`);
//     }
//   }
// }

// // Save data to localStorage
// function saveToLocalStorage(config) {
//   localStorage.setItem(config.name, JSON.stringify(config.list));
// }
// //  Save Item (Add/Edit)
// function saveItem(config) {
//   const item = getFormData(config.fields);
//   const index = document.getElementById(config.indexField).value;

//   if (index === "") {
//     config.list.push(item);
//   } else {
//     config.list[index] = item;
//   }

//   resetForm(config);
//   saveToLocalStorage(config);
//   renderList(config);
//   closeModal(config);
// }

// //  Delete Item
// function deleteItem(config, index) {
//   if (!confirm(`Are you sure you want to delete this ${config.singularName}?`))
//     return;

//   config.list.splice(index, 1);
//   saveToLocalStorage(config);
//   renderList(config);
// }
// function initializeModule(config) {
//   if (config.useLocalStorage) {
//     loadFromLocalStorage(config);
//   }

//   renderList(config);
//   setupSearch(config);
// }
// function renderList(config) {
//   const tableBody = document.getElementById(config.tableBodyId);
//   tableBody.innerHTML = "";

//   const items = applySearchFilter(config.list, config.searchFilters);

//   const paginatedItems = getPaginatedItems(config, items);

//   if (paginatedItems.length === 0) {
//     tableBody.innerHTML = `
//       <tr>
//         <td colspan="${
//           config.columnCount || 6
//         }" class="text-center text-muted">No ${config.name} found.</td>
//       </tr>`;
//   } else {
//     paginatedItems.forEach((item, index) => {
//       const globalIndex =
//         (config.currentPage - 1) * config.itemsPerPage + index;
//       tableBody.innerHTML += config.renderRow(item, globalIndex);
//     });
//   }

//   renderPagination(config, items.length);
//   updateBadgeCount(config, items.length);
// }
// //  Update Badge Count
// function updateBadgeCount(config, count) {
//   if (!config.badgeId) return;
//   const badge = document.getElementById(config.badgeId);
//   if (badge) badge.textContent = count;
// }
// // badgeId: "staffCountBadge";

// //  Track current sorting
// function sortList(config, sortKey) {
//   if (config.sortKey === sortKey) {
//     config.sortOrder = config.sortOrder === "asc" ? "desc" : "asc";
//   } else {
//     config.sortKey = sortKey;
//     config.sortOrder = "asc";
//   }

//   config.list.sort((a, b) => {
//     let valA = a[sortKey];
//     let valB = b[sortKey];

//     if (typeof valA === "string") valA = valA.toLowerCase();
//     if (typeof valB === "string") valB = valB.toLowerCase();

//     if (valA < valB) return config.sortOrder === "asc" ? -1 : 1;
//     if (valA > valB) return config.sortOrder === "asc" ? 1 : -1;
//     return 0;
//   });

//   renderList(config);
// }

// function updateSortIcons(config) {
//   if (!config.sortKey) return;

//   const fields = ["name", "role", "phone", "price", "category", "state"]; // common keys
//   fields.forEach((field) => {
//     const iconEl = document.getElementById(`${config.name}SortIcon_${field}`);
//     if (iconEl) {
//       if (config.sortKey === field) {
//         iconEl.innerHTML = config.sortOrder === "asc" ? "↑" : "↓";
//       } else {
//         iconEl.innerHTML = "";
//       }
//     }
//   });
// }
// // sortKey: null,
// // sortOrder: "asc",
// function exportToCSV(config) {
//   const items = config.list;
//   if (!items.length) return alert("No data to export.");

//   const headers = Object.keys(items[0]);
//   const csvRows = [
//     headers.join(","), // Header row
//     ...items.map((item) =>
//       headers.map((field) => `"${item[field]}"`).join(",")
//     ),
//   ];

//   const blob = new Blob([csvRows.join("\n")], { type: "text/csv" });
//   const url = URL.createObjectURL(blob);

//   const a = document.createElement("a");
//   a.href = url;
//   a.download = `${config.name}_data.csv`;
//   document.body.appendChild(a);
//   a.click();
//   document.body.removeChild(a);
// }
// function exportToPDF(config) {
//   if (!config.list.length) return alert("No data to export.");

//   const doc = new jspdf.jsPDF();
//   const headers = [Object.keys(config.list[0])];
//   const data = config.list.map((item) => headers[0].map((k) => item[k]));

//   doc.text(`${config.name.toUpperCase()} Report`, 14, 15);
//   doc.autoTable({
//     startY: 20,
//     head: headers,
//     body: data,
//     styles: { fontSize: 8 },
//   });

//   doc.save(`${config.name}_data.pdf`);
// }
// //  Auto-initialize on page load
// // document.addEventListener("DOMContentLoaded", () => {
// //   // Har module ke config ke according initialize karo
// //   if (typeof moduleConfigs !== "undefined") {
// //     if (moduleConfigs.staff) initializeModule(moduleConfigs.staff);
// //     if (moduleConfigs.product) initializeModule(moduleConfigs.product);
// //     if (moduleConfigs.service) initializeModule(moduleConfigs.service);
// //     if (moduleConfigs.branch) initializeModule(moduleConfigs.branch);
// //   }
// // });

// document.addEventListener("DOMContentLoaded", () => {
//   Object.values(moduleConfigs).forEach((config) => {
//     initializeModule(config);
//   });
// });

// // main.js ke andar
// window.addEventListener("DOMContentLoaded", () => {
//   if (typeof moduleConfigs !== "undefined") {
//     Object.values(moduleConfigs).forEach(initializeModule);
//   }
// });
