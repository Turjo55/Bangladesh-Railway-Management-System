# Test Plan & Test Cases
## Bangladesh Railway Management System

---

## 1. Introduction
The Test Plan documents the strategy that will be used to verify and ensure that the "Bangladesh Railway Management System" functions as expected. It describes the test scope, approach, resources, and schedule of intended test activities.

### 1.1 Objectives
-   To validate the correctness of the code and business logic.
-   To ensure the system is robust and handles errors gracefully.
-   To verify that all functional requirements (as per SRS) are met.

### 1.2 Scope
-   **In Scope:** User Authentication, Search Module, Booking Logic, Admin Panel, Database Constraints.
-   **Out of Scope:** Load testing with real payment gateways (simulated only).

---

## 2. Test Strategy

### 2.1 Unit Testing
Tests individual components or functions in isolation appropriately mocking dependencies.
-   **Tools:** Mocha/Chai (for Node.js) or Manual Verification scripts.
-   **Focus:** Model validation, Helper functions.

### 2.2 Integration Testing
Verifies the interaction between different modules (e.g., API <-> Database).
-   **Focus:** API endpoints return correct HTTP status codes and JSON structure.

### 2.3 System Testing (UAT)
End-to-end testing of complete user flows via the browser interface.
-   **Focus:** Entire workflows like "Book a Ticket" or "Add a Train".

---

## 3. Test Cases

### 3.1 Authentication Module

| Test Case ID | Test Description | Pre-Condition | Test Steps | Expected Result | Priority |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-AUTH-01** | Verify valid registration | User is not logged in | 1. Navigate to Register.<br>2. Fill unique email, password, name.<br>3. Submit. | User created, redirect to login. | High |
| **TC-AUTH-02** | Verify duplicate registration | User exists with email 'X' | 1. Register with email 'X'. | Error: "Email already exists". | High |
| **TC-AUTH-03** | Verify valid login | User registered | 1. Enter correct credentials.<br>2. Submit. | Login successful, session started. | High |
| **TC-AUTH-04** | Verify invalid login | User registered | 1. Enter wrong password. | Error: "Invalid credentials". | High |

### 3.2 Search Module

| Test Case ID | Test Description | Pre-Condition | Test Steps | Expected Result | Priority |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-SRCH-01** | Search with valid route | Routes exist | 1. Select 'Dhaka' to 'Chittagong'.<br>2. Pick Date.<br>3. Click Search. | List of trains displayed. | High |
| **TC-SRCH-02** | Search with no route | No route exists | 1. Select 'Dhaka' to 'Nowhere'. | Msg: "No trains found". | Medium |

### 3.3 Booking Module

| Test Case ID | Test Description | Pre-Condition | Test Steps | Expected Result | Priority |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-BOOK-01** | Book available seat | User logged in | 1. Select Train.<br>2. Click 'Book'.<br>3. Select Seat 'A1'.<br>4. Confirm. | Booking confirmed, PNR generated. | Critical |
| **TC-BOOK-02** | Double booking prevention | Seat 'A1' booked | 1. User B selects Seat 'A1'.<br>2. Click 'Book'. | Error: "Seat already taken". | Critical |

### 3.4 Admin Module

| Test Case ID | Test Description | Pre-Condition | Test Steps | Expected Result | Priority |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-ADM-01** | Add new train | Admin logged in | 1. Go to Train Manager.<br>2. Add 'Test Express'.<br>3. Save. | Train appears in list. | High |
| **TC-ADM-02** | Delete train | Train exists | 1. Click delete icon on train. | Train removed from list & DB. | Medium |
| **TC-ADM-03** | View all bookings | Bookings exist | 1. Go to Booking Manager. | Table displays user bookings. | Medium |

---

## 4. Defect Reporting
Defects found during testing will be logged with the following attributes:
-   **Defect ID**
-   **Severity** (Critical/Major/Minor)
-   **Steps to Reproduce**
-   **Status** (New/Open/Fixed/Closed)

---

## 5. Exit Criteria
Testing will be considered complete when:
-   All "High" and "Critcal" priority test cases pass.
-   No critical bugs remain open.
-   User Acceptance Testing (UAT) is signed off.
