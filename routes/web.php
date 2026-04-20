<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\cdc\CDCDashBoardController;
use App\Http\Controllers\cdc\CDCDepartmentController;
use App\Http\Controllers\cdc\CDCSchemeCategoryController;
use App\Http\Controllers\cdc\CDCSchemeController;
use App\Http\Controllers\cdc\CDCSchemeVerificationController;
use App\Http\Controllers\cdc\CDCUserController;
// use App\Http\Controllers\cdc_dept\CDCDEPTDashBoardController;
use App\Http\Controllers\expert\EXPERTDashBoardController;
use App\Http\Controllers\expert\EXPERTSyllabusSubmissionController;
use App\Http\Controllers\expert\EXPERTSyllabusController;
use App\Http\Controllers\hod\HODAssignCourseController;
use App\Http\Controllers\hod\HODClassAwardController;
use App\Http\Controllers\hod\HODCourseController;
use App\Http\Controllers\hod\HODDashBoardController;
use App\Http\Controllers\hod\HODELectiveGroupController;
use App\Http\Controllers\hod\HODPSOController;
use App\Http\Controllers\hod\HODSyllabusApprovalController;
use App\Http\Controllers\hod\HODUserController;
use App\Http\Controllers\moderator\MODERATORDashBoardController;
use App\Http\Controllers\moderator\MODERATORSyllabusApprovalController;
use Illuminate\Support\Facades\Route;

// To add role to middlware function

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:cdc'])->prefix('/cdc')->name('cdc.')->group(function () {

    Route::get('/dashboard', [CDCDashBoardController::class, 'dashboard']);

    Route::get('/departments', [CDCDepartmentController::class, 'index'])
        ->name('departments.index');

    Route::post('/departments', [CDCDepartmentController::class, 'store'])->name('departments.store');

    Route::put('/departments/{id}', [CDCDepartmentController::class, 'update'])->name('departments.update');

    Route::delete('/departments/{id}', [CDCDepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('/users', [CDCUserController::class, 'index'])->name('users.index');

    Route::post('/users', [CDCUserController::class, 'store'])->name('users.store');

    Route::put('/users/{id}', [CDCUserController::class, 'update'])->name('users.update');

    Route::delete('/users/{id}', [CDCUserController::class, 'destroy'])->name('users.destroy');

    Route::get('/schemes/create', [CDCSchemeController::class, 'create'])->name('schemes.create');

    Route::post('/schemes', [CDCSchemeController::class, 'store'])->name('schemes.store');

    // Page 2 (categories)
    Route::get('/schemes/{scheme}/categories', [CDCSchemeCategoryController::class, 'create'])
        ->name('schemes.categories.create');

    Route::post('/schemes/{scheme}/categories', [CDCSchemeCategoryController::class, 'store'])->name('schemes.categories.store');

    Route::patch('/schemes/categories/{category}', [CDCSchemeCategoryController::class, 'update'])->name('schemes.categories.update');

    Route::delete('/schemes/categories/{category}', [CDCSchemeCategoryController::class, 'destroy'])->name('schemes.categories.destroy');

    Route::post('/schemes/{scheme}/categories/next', [CDCSchemeCategoryController::class, 'next'])->name('schemes.categories.next');

    Route::get('/schemes/{scheme}/po', [CDCSchemeController::class, 'createPO'])
        ->name('schemes.po.create');

    Route::post('/schemes/{scheme}/po', [CDCSchemeController::class, 'storePO'])
        ->name('schemes.po.store');

    Route::get('/schemes/create/next', [CDCSchemeController::class, 'nextAfterCreate'])->name('schemes.create.next');

    Route::get('/schemes/edit/next', [CDCSchemeController::class, 'nextAfterEdit'])->name('schemes.edit.next');

    // Edit Scheme - Page 1 (listing)
    Route::get('/schemes/edit', [CDCSchemeController::class, 'editIndex'])->name('schemes.edit.index');

    // Edit Scheme - Page 2 (details form)
    Route::get('/schemes/{scheme}/edit', [CDCSchemeController::class, 'edit'])->name('schemes.edit');

    // update scheme details
    Route::patch('/schemes/{scheme}', [CDCSchemeController::class, 'update'])->name('schemes.update');

    // next → categories page
    Route::post('/schemes/{scheme}/edit/categories', [CDCSchemeController::class, 'editCategoriesRedirect'])->name('schemes.edit.next');

    // Show page
    Route::get('/schemes/{scheme}/award-rules', [CDCSchemeController::class, 'createAwardRules'])
        ->name('schemes.award.create');

    // Store rules
    Route::post('/schemes/{scheme}/award-rules', [CDCSchemeController::class, 'storeAwardRules'])
        ->name('schemes.award.store');

    // EDIT FLOW - Categories
    Route::get('/schemes/{scheme}/edit/categories', [CDCSchemeController::class, 'categories'])
        ->name('schemes.edit.categories');

    Route::post('/schemes/{scheme}/edit/categories', [CDCSchemeController::class, 'storeCategory'])
        ->name('schemes.edit.categories.store');

    Route::patch('/schemes/edit/categories/{category}', [CDCSchemeController::class, 'updateCategory'])
        ->name('schemes.edit.categories.update');

    Route::delete('/schemes/edit/categories/{category}', [CDCSchemeController::class, 'destroyCategory'])
        ->name('schemes.edit.categories.destroy');

    Route::post('/schemes/{scheme}/edit/categories/next', [CDCSchemeController::class, 'nextAfterCategories'])
        ->name('schemes.edit.categories.next');

    // Show edit award rules page
    Route::get('/schemes/{scheme}/edit/award-rules', [CDCSchemeController::class, 'editAwardRules'])
        ->name('schemes.edit.award');

    // Update rules
    Route::patch('/schemes/{scheme}/edit/award-rules', [CDCSchemeController::class, 'updateAwardRules'])
        ->name('schemes.edit.award.update');

    Route::post('/schemes/{scheme}/edit/award-rules/next', [CDCSchemeController::class, 'nextAfterClassAward'])
        ->name('schemes.edit.award.next');

    Route::get('/schemes/{scheme}/po/edit', [CDCSchemeController::class, 'editPO'])
        ->name('schemes.po.edit');

    Route::post('/schemes/{scheme}/po/update', [CDCSchemeController::class, 'updatePO'])
        ->name('schemes.po.update');

    // Manage schemes page
    Route::get('/schemes/manage', [CDCSchemeController::class, 'manage'])
        ->name('schemes.manage');

    // Toggle active
    Route::patch('/schemes/{scheme}/toggle-active', [CDCSchemeController::class, 'toggleActive'])
        ->name('schemes.toggleActive');

    // Toggle lock
    Route::patch('/schemes/{scheme}/toggle-lock', [CDCSchemeController::class, 'toggleLock'])
        ->name('schemes.toggleLock');

    // Delete scheme
    Route::delete('/schemes/{scheme}', [CDCSchemeController::class, 'destroy'])
        ->name('schemes.destroy');

    // Verify - Page 1 (schemes list)
    Route::get('/schemes/verify', [CDCSchemeVerificationController::class, 'index'])
        ->name('schemes.verify.index');

    // Verify - Page 2 (departments list)
    Route::get('/schemes/{scheme}/verify', [CDCSchemeVerificationController::class, 'departments'])
        ->name('schemes.verify.departments');

    Route::get('/schemes/{scheme}/verify/{department}', [CDCSchemeVerificationController::class, 'departmentDetail'])
        ->name('schemes.verify.department.detail');

    Route::get('/schemes/{scheme}/verify/{department}/semesters', [CDCSchemeVerificationController::class, 'semesterList'])
        ->name('schemes.verify.semesters');

    Route::get('/schemes/{scheme}/verify/{department}/semesters/{semester}', [CDCSchemeVerificationController::class, 'semesterPreview'])
        ->name('schemes.verify.semester.preview');

});

// Route::middleware(['auth', 'role:cdc-dept'])->prefix('/cdc-dept')->name('cdc-dept.')->group(function () {

//     Route::get('/dashboard', [CDCDEPTDashBoardController::class, 'dashboard']);

// });

Route::middleware(['auth', 'role:hod', 'active.scheme'])->prefix('/hod')->name('hod.')->group(function () {

    Route::get('/dashboard', [HODDashBoardController::class, 'dashboard']);

    Route::get('/pso', [HODPSOController::class, 'pso'])
        ->name('pso');

    Route::post('/pso', [HODPSOController::class, 'savePso'])
        ->name('pso.save');

    Route::get('/courses/create', [HODCourseController::class, 'create'])
        ->name('courses.create');

    Route::post('/courses/store', [HODCourseController::class, 'store'])
        ->name('courses.store');

    // AJAX for common course search
    Route::get('/courses/search-common', [HODCourseController::class, 'searchCommon'])
        ->name('courses.search.common');

    Route::get('/courses/{id}/details', [HODCourseController::class, 'getCourseDetails'])->name('courses.details');

    Route::get('/courses/view', [HODCourseController::class, 'view'])
        ->name('courses.view');

    Route::delete('/courses/{offering}', [HODCourseController::class, 'destroy'])
        ->name('courses.destroy');

    Route::get('/courses/{offering}/edit', [HODCourseController::class, 'edit'])
        ->name('courses.edit');

    Route::patch('/courses/{offering}', [HODCourseController::class, 'update'])
        ->name('courses.update');

    Route::get('/courses/common/{course}/edit', [HODCourseController::class, 'commonEdit'])
        ->name('courses.common.edit');

    Route::patch('/courses/common/{course}', [HODCourseController::class, 'commonUpdate'])
        ->name('courses.common.update');

    Route::delete('/courses/common/{course}', [HODCourseController::class, 'commonDestroy'])
        ->name('courses.common.destroy');

    Route::get('/elective-groups', [HODELectiveGroupController::class, 'index'])
        ->name('elective.index');

    Route::post('/elective-groups', [HODELectiveGroupController::class, 'store'])
        ->name('elective.store');

    Route::post('/elective-groups/add-course', [HODELectiveGroupController::class, 'addCourse'])
        ->name('elective.addCourse');

    Route::delete('/elective-groups/{group}', [HODELectiveGroupController::class, 'destroy'])
        ->name('elective.destroy');

    Route::get('/class-award', [HODClassAwardController::class, 'index'])
        ->name('class_award.index');

    Route::post('/class-award', [HODClassAwardController::class, 'store'])
        ->name('class_award.store');

    Route::get('/assign-courses', [HODAssignCourseController::class, 'index'])
        ->name('assign.index');

    Route::post('/assign-courses', [HODAssignCourseController::class, 'store'])
        ->name('assign.store');

    Route::get('/syllabus', [HODSyllabusApprovalController::class, 'syllabusIndex'])
        ->name('syllabus.index');

    Route::post('/syllabus/{syllabus}/approve', [HODSyllabusApprovalController::class, 'approve'])
        ->name('syllabus.approve');

    Route::get('/syllabus/{course}/preview', [HODSyllabusApprovalController::class, 'preview'])
        ->name('syllabus.preview');

    Route::get('/users/moderator', [HODUserController::class, 'moderatorIndex'])->name('users.moderator.index');

    Route::post('/users/moderator', [HODUserController::class, 'store'])->name('users.moderator.store');

    Route::put('/users/moderator/{id}', [HODUserController::class, 'update'])->name('users.moderator.update');

    Route::delete('/users/moderator/{id}', [HODUserController::class, 'destroy'])->name('users.moderator.destroy');

    Route::get('/users/expert', [HODUserController::class, 'expertIndex'])->name('users.expert.index');

    Route::post('/users/expert', [HODUserController::class, 'store'])->name('users.expert.store');

    Route::put('/users/expert/{id}', [HODUserController::class, 'update'])->name('users.expert.update');

    Route::delete('/users/expert/{id}', [HODUserController::class, 'destroy'])->name('users.expert.destroy');

});

Route::middleware(['auth', 'role:moderator'])->prefix('/moderator')->name('moderator.')->group(function () {

    Route::get('/dashboard', [MODERATORDashBoardController::class, 'dashboard']);

    Route::get('/syllabus', [MODERATORSyllabusApprovalController::class, 'index'])
    ->name('syllabus.index');

Route::post('/syllabus/{syllabus}/approve', [MODERATORSyllabusApprovalController::class, 'approve'])
    ->name('syllabus.approve');

Route::post('/syllabus/{syllabus}/reject', [MODERATORSyllabusApprovalController::class, 'reject'])
    ->name('syllabus.reject');

Route::get('/syllabus/{course}/preview', [MODERATORSyllabusApprovalController::class, 'preview'])
    ->name('syllabus.preview');

});

Route::middleware(['auth', 'role:expert', 'active.scheme'])->prefix('/expert')->name('expert.')->group(function () {

    Route::get('/dashboard', [EXPERTDashBoardController::class, 'index'])->name('dashboard');

    Route::get('/syllabus', [EXPERTSyllabusSubmissionController::class, 'expertIndex'])
        ->name('syllabus.index');

    Route::post('/syllabus/{course}/submit', [EXPERTSyllabusSubmissionController::class, 'submit'])
        ->name('syllabus.submit');

    Route::prefix('/syllabus/{course}')->name('syllabus.')->group(function () {

        Route::get('/sections', [EXPERTSyllabusController::class, 'sections'])
            ->name('sections');

        Route::get('/preview', [EXPERTSyllabusController::class, 'preview'])
            ->name('preview');

        Route::get('/rationale', [EXPERTSyllabusController::class, 'rationale'])
            ->name('rationale');

        Route::post('/rationale', [EXPERTSyllabusController::class, 'saveRationale'])
            ->name('rationale.save');

        Route::get('/industrial-outcome', [EXPERTSyllabusController::class, 'industrialOutcome'])
            ->name('industrial');

        Route::post('/industrial-outcome', [EXPERTSyllabusController::class, 'saveIndustrialOutcome'])
            ->name('industrial.save');

        Route::get('/course-outcome', [EXPERTSyllabusController::class, 'courseOutcome'])
            ->name('co');

        Route::post('/course-outcome', [EXPERTSyllabusController::class, 'saveCourseOutcome'])
            ->name('co.save');

        Route::get('/course-details', [EXPERTSyllabusController::class, 'courseDetails'])
            ->name('details');

        Route::post('/course-details', [EXPERTSyllabusController::class, 'saveCourseDetails'])
            ->name('details.save');

        Route::get('/specification', [EXPERTSyllabusController::class, 'specification'])
            ->name('specification');

        Route::post('/specification', [EXPERTSyllabusController::class, 'saveSpecification'])
            ->name('specification.save');

        Route::get('/practicals', [EXPERTSyllabusController::class, 'practicals'])
            ->name('practicals');

        Route::post('/practicals', [EXPERTSyllabusController::class, 'savePracticals'])
            ->name('practicals.save');

        Route::get('/self-learning', [EXPERTSyllabusController::class, 'selfLearning'])
            ->name('self-learning');

        Route::post('/self-learning', [EXPERTSyllabusController::class, 'saveSelfLearning'])
            ->name('self-learning.save');

        Route::get('/tutorial', [EXPERTSyllabusController::class, 'tutorial'])
            ->name('tutorial');

        Route::post('/tutorial', [EXPERTSyllabusController::class, 'saveTutorial'])
            ->name('tutorial.save');

        Route::get('/instruction', [EXPERTSyllabusController::class, 'instruction'])
            ->name('instruction');

        Route::post('/instruction', [EXPERTSyllabusController::class, 'saveInstruction'])
            ->name('instruction.save');

        Route::get('/assessment', [EXPERTSyllabusController::class, 'assessment'])
            ->name('assessment');

        Route::get('/books', [EXPERTSyllabusController::class, 'books'])
            ->name('books');

        Route::post('/books', [EXPERTSyllabusController::class, 'saveBooks'])
            ->name('books.save');

        Route::get('/websites', [EXPERTSyllabusController::class, 'websites'])
            ->name('websites');

        Route::post('/websites', [EXPERTSyllabusController::class, 'saveWebsites'])
            ->name('websites.save');

        Route::get('/equipment', [EXPERTSyllabusController::class, 'equipments'])
            ->name('equipment');

        Route::post('/equipments', [EXPERTSyllabusController::class, 'saveEquipments'])
            ->name('equipments.save');

        Route::get('/mapping', [EXPERTSyllabusController::class, 'mapping'])
            ->name('mapping');

        Route::post('/mapping', [EXPERTSyllabusController::class, 'saveMapping'])
            ->name('mapping.save');

        Route::get('/question-paper-profile', [EXPERTSyllabusController::class, 'questionPaperProfile'])
            ->name('qpp');

        Route::post('/question-paper-profile', [EXPERTSyllabusController::class, 'saveQuestionPaperProfile'])
            ->name('qpp.save');

        Route::get('/question-bits', [EXPERTSyllabusController::class, 'questionBits'])
            ->name('qb');

        Route::post('/question-bits', [EXPERTSyllabusController::class, 'saveQuestionBits'])
            ->name('qb.save');

    });

});

Route::fallback(function () {
    return 'Fallback Route';
});
