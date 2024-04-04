<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProblemOptionRepository;
use App\Repositories\ProblemRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $categoryRepository;
    protected $problemRepository;
    protected $problemOptionRepository;
    protected $userRepository;
    protected $adminRepository;

    public function __construct(CategoryRepository $categoryRepository,ProblemRepository $problemRepository,ProblemOptionRepository $problemOptionRepository,UserRepository $userRepository,AdminRepository $adminRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->problemRepository = $problemRepository;
        $this->problemOptionRepository = $problemOptionRepository;
        $this->userRepository = $userRepository;
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        $categories = $this->categoryRepository->getAllCategoriesCount();
        $problems = $this->problemRepository->getAllProblemsCount();
        $problemOptions = $this->problemOptionRepository->getAllProblemOptionsCount();
        $users = $this->userRepository->getAllUsersCount();
        $admins = $this->adminRepository->getAllUsersCount();
        return view('vue-pages.dashboard.browse',compact('categories','problems','problemOptions','users','admins'));
    }
}
