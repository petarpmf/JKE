<?php
namespace App\Http\Interfaces;

interface DashboardInterface
{
    public function seekingJobPosition();
    public function totalActiveCandidates($roleId);
    public function recentlyAddedCandidates($perPage, $roleId);
    public function numberOfProjects();
    //public function recentActivity($roleId, $perPage);
    public function recentActivityViewAll($queryString, $roleId, $perPage);
    //public function recentCreated($roleId, $perPage);
    //public function recentUpdated($roleId, $perPage);
    //public function recentLogged($roleId, $perPage);
    public function recentProjectTeamActivity($userId, $roleId, $perPage);

}