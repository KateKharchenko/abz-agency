<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ABZ Agency Test</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        
        <!-- Custom styles -->
        <style>
            body {
                padding: 20px 0;
                background-color: #f8f9fa;
            }
            .pagination-controls {
                margin: 20px 0;
            }
            .user-table {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .loading {
                display: none;
                text-align: center;
                padding: 20px;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center my-4">
                <h1>ABZ Agency Users</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-circle me-1"></i> Add New User
                </button>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6 offset-md-3">
                    <div class="d-flex justify-content-between align-items-center pagination-controls">
                        <div class="form-group d-flex align-items-center">
                            <label for="usersPerPage" class="me-2 mb-0">Users per page:</label>
                            <select id="usersPerPage" class="form-select" style="width: auto">
                                <option value="6">6</option>
                                <option value="12">12</option>
                                <option value="24">24</option>
                                <option value="48">48</option>
                            </select>
                        </div>
                        <div class="pagination-nav">
                            <button id="prevPage" class="btn btn-outline-primary me-2" disabled>Previous</button>
                            <span id="currentPage">Page 1</span>
                            <button id="nextPage" class="btn btn-outline-primary ms-2">Next</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="loading" id="loadingIndicator">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading users...</p>
                    </div>
                    
                    <div class="table-responsive user-table">
                        <table class="table table-hover" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <!-- User data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-danger mt-3 d-none" id="errorMessage">
                        Failed to load users. Please try again later.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Profile Modal -->
        <div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userProfileModalLabel">User Profile #<span id="userProfileId"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4" id="userProfilePhoto">
                            <!-- User photo will be displayed here -->
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Name</h6>
                                <p class="fw-bold" id="userProfileName"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Position</h6>
                                <p class="fw-bold" id="userProfilePosition"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Email</h6>
                                <p id="userProfileEmail"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted">Phone</h6>
                                <p id="userProfilePhone"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addUserForm" enctype="multipart/form-data">
                            <div class="alert alert-danger d-none" id="formErrorMessage"></div>
                            <div class="alert alert-success d-none" id="formSuccessMessage">User created successfully!</div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="john@example.com">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="+380XXXXXXXXX">
                                        <div class="form-text">Format: +380XXXXXXXXX</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="position_id" class="form-label">Position</label>
                                        <select class="form-select" id="position_id" name="position_id">
                                            <option value="" selected disabled>Select position</option>
                                            <!-- Positions will be loaded dynamically -->
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Max size: 2MB. Formats: JPG, PNG</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="submitUserBtn">Create User</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- User List Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Elements - User List
                const usersTableBody = document.getElementById('usersTableBody');
                const loadingIndicator = document.getElementById('loadingIndicator');
                const errorMessage = document.getElementById('errorMessage');
                const usersPerPageSelect = document.getElementById('usersPerPage');
                const prevPageBtn = document.getElementById('prevPage');
                const nextPageBtn = document.getElementById('nextPage');
                const currentPageSpan = document.getElementById('currentPage');
                
                // Elements - User Form
                const addUserForm = document.getElementById('addUserForm');
                const formErrorMessage = document.getElementById('formErrorMessage');
                const formSuccessMessage = document.getElementById('formSuccessMessage');
                
                // Variable to store the registration token
                let registrationToken = '';
                const submitUserBtn = document.getElementById('submitUserBtn');
                const positionSelect = document.getElementById('position_id');
                const addUserModal = document.getElementById('addUserModal');
                
                // Pagination state
                let currentPage = 1;
                let totalPages = 1;
                let usersPerPage = 6;
                
                // Load users and positions on page load
                loadUsers();
                loadPositions();
                
                // Event listeners - User List
                usersPerPageSelect.addEventListener('change', function() {
                    usersPerPage = parseInt(this.value);
                    currentPage = 1;
                    loadUsers();
                });
                
                prevPageBtn.addEventListener('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        loadUsers();
                    }
                });
                
                nextPageBtn.addEventListener('click', function() {
                    if (currentPage < totalPages) {
                        currentPage++;
                        loadUsers();
                    }
                });
                
                // Event listeners - User Form
                submitUserBtn.addEventListener('click', function() {
                    submitUserForm();
                });
                
                // Reset form when modal is closed
                addUserModal.addEventListener('hidden.bs.modal', function () {
                    resetUserForm();
                });
                
                // Function to load users
                function loadUsers() {
                    // Show loading indicator
                    loadingIndicator.style.display = 'block';
                    usersTableBody.innerHTML = '';
                    errorMessage.classList.add('d-none');
                    
                    // Update pagination UI
                    updatePaginationUI();
                    
                    // Fetch users from API
                    fetch(`/api/users?page=${currentPage}&count=${usersPerPage}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Hide loading indicator
                            loadingIndicator.style.display = 'none';
                            
                            // Update pagination state
                            totalPages = data.total_pages;
                            
                            // Update pagination UI again with total pages info
                            updatePaginationUI();
                            
                            // Render users
                            renderUsers(data.users);
                        })
                        .catch(error => {
                            console.error('Error fetching users:', error);
                            loadingIndicator.style.display = 'none';
                            errorMessage.classList.remove('d-none');
                        });
                }
                
                // Function to render users
                function renderUsers(users) {
                    if (users.length === 0) {
                        const emptyRow = document.createElement('tr');
                        emptyRow.innerHTML = `<td colspan="6" class="text-center">No users found</td>`;
                        usersTableBody.appendChild(emptyRow);
                        return;
                    }
                    
                    users.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.phone || 'N/A'}</td>
                            <td>${user.position.name || 'N/A'}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-profile" data-user-id="${user.id}">
                                    <i class="bi bi-eye me-1"></i>
                                </button>
                            </td>
                        `;
                        usersTableBody.appendChild(row);
                        
                        // Add event listener to the button
                        const viewButton = row.querySelector('.view-profile');
                        viewButton.addEventListener('click', () => viewUserProfile(user));
                    });
                }
                
                // Function to update pagination UI
                function updatePaginationUI() {
                    currentPageSpan.textContent = `Page ${currentPage} of ${totalPages}`;
                    prevPageBtn.disabled = currentPage <= 1;
                    nextPageBtn.disabled = currentPage >= totalPages;
                }
                
                // Function to load positions
                function loadPositions() {
                    fetch('/api/positions', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const positions = data.positions;
                            
                            // Clear existing options except the first one
                            while (positionSelect.options.length > 1) {
                                positionSelect.remove(1);
                            }
                            
                            // Add positions to select
                            positions.forEach(position => {
                                const option = document.createElement('option');
                                option.value = position.id;
                                option.textContent = position.name;
                                positionSelect.appendChild(option);
                            });
                        } else {
                            console.error('Error loading positions:', data.message);
                            formErrorMessage.textContent = 'Failed to load positions. Please try again later.';
                            formErrorMessage.classList.remove('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading positions:', error);
                        formErrorMessage.textContent = 'Failed to load positions. Please try again later.';
                        formErrorMessage.classList.remove('d-none');
                    });
                }
                
                // Function to fetch a token
                function fetchToken() {
                    return fetch('/api/token', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            return data.token;
                        } else {
                            throw new Error(data.message || 'Failed to get token');
                        }
                    });
                }
                
                // Function to submit user form
                function submitUserForm() {
                    // Reset messages
                    formErrorMessage.classList.add('d-none');
                    formSuccessMessage.classList.add('d-none');
                    
                    // Disable submit button during submission
                    submitUserBtn.disabled = true;
                    submitUserBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...';
                    
                    // First get a token
                    fetchToken()
                        .then(token => {
                            // Create FormData object
                            const formData = new FormData(addUserForm);
                            
                            // Submit form data with token in header
                            return fetch('/api/users', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': 'Bearer ' + token
                                    // Don't set Content-Type header when using FormData
                                },
                                body: formData
                            });
                        })
                        .then(response => {
                            // Re-enable submit button
                            submitUserBtn.disabled = false;
                            submitUserBtn.innerHTML = 'Create User';
                            
                            // Parse response
                            return response.json().then(data => {
                                if (!response.ok) {
                                    // Server validation error
                                    if (data.fails) {
                                        // Clear previous validation errors
                                        clearValidationErrors();
                                        
                                        // Show validation errors
                                        showValidationErrors(data.fails);
                                        
                                        throw new Error('Please correct the validation errors');
                                    } else {
                                        throw new Error(data.message || 'Error creating user');
                                    }
                                }
                                return data;
                            });
                        })
                    .then(data => {
                        // Show success message
                        formSuccessMessage.classList.remove('d-none');
                        
                        // Reset form
                        addUserForm.reset();
                        
                        // Reload users list after a short delay
                        setTimeout(() => {
                            loadUsers();
                            // Close modal after success
                            const modal = bootstrap.Modal.getInstance(addUserModal);
                            modal.hide();
                        }, 1500);
                    })
                    .catch(error => {
                        // Show error message
                        formErrorMessage.textContent = error.message || 'An error occurred while creating the user.';
                        formErrorMessage.classList.remove('d-none');
                        console.error('Error creating user:', error);
                    });
                }
                
                // Function to reset user form
                function resetUserForm() {
                    addUserForm.reset();
                    formErrorMessage.classList.add('d-none');
                    formSuccessMessage.classList.add('d-none');
                    clearValidationErrors();
                }
                
                // Function to view user profile
                function viewUserProfile(userPreview) {
                    // Show loading in the modal
                    const photoContainer = document.getElementById('userProfilePhoto');
                    photoContainer.innerHTML = `<div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>`;
                    
                    document.getElementById('userProfileName').textContent = 'Loading...';
                    document.getElementById('userProfilePosition').textContent = 'Loading...';
                    document.getElementById('userProfileEmail').textContent = 'Loading...';
                    document.getElementById('userProfilePhone').textContent = 'Loading...';
                    document.getElementById('userProfileId').textContent = userPreview.id;
                    
                    // Show the modal
                    const userProfileModal = new bootstrap.Modal(document.getElementById('userProfileModal'));
                    userProfileModal.show();
                    
                    // Fetch detailed user data from API
                    fetch(`/api/users/${userPreview.id}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const user = data.user;
                            
                            // Update modal with user details
                            document.getElementById('userProfileName').textContent = user.name;
                            document.getElementById('userProfilePosition').textContent = user.position.name || 'N/A';
                            document.getElementById('userProfileEmail').textContent = user.email;
                            document.getElementById('userProfilePhone').textContent = user.phone || 'N/A';
                            
                            // Set user photo if available
                            if (user.photo) {
                                photoContainer.innerHTML = `<img src="/storage/users/${user.photo}" alt="${user.name}" class="img-thumbnail rounded-circle" style="width: 70px; height: 70px; object-fit: cover;">`;
                            } else {
                                photoContainer.innerHTML = `<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; margin: 0 auto;">
                                    <span style="font-size: 2rem;">${user.name.charAt(0).toUpperCase()}</span>
                                </div>`;
                            }
                        } else {
                            // Show error message
                            photoContainer.innerHTML = `<div class="alert alert-danger">${data.message || 'Error loading user data'}</div>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching user details:', error);
                        photoContainer.innerHTML = `<div class="alert alert-danger">Error loading user data</div>`;
                    });
                }
                
                // Function to clear validation errors
                function clearValidationErrors() {
                    // Remove all error messages
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                    
                    // Remove red borders from all inputs
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                }
                
                // Function to show validation errors
                function showValidationErrors(fails) {
                    // Update the error message
                    formErrorMessage.textContent = 'Please correct the validation errors';
                    formErrorMessage.classList.remove('d-none');
                    
                    // Add error messages and red borders to each field with errors
                    Object.keys(fails).forEach(field => {
                        const input = document.getElementById(field);
                        if (input) {
                            // Add red border
                            input.classList.add('is-invalid');
                            
                            // Add error message below the field
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = fails[field][0]; // First error message
                            
                            // Insert after the input
                            input.parentNode.insertBefore(errorDiv, input.nextSibling);
                        }
                    });
                }
            });
        </script>
    </body>
</html>
