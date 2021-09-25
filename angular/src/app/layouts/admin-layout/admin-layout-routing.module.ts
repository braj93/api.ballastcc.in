import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { DashboardComponent,DashboardResolver } from '../../dashboard';
import { StudentComponent } from '../../student';
import { MasterComponent,MasterResolver } from '../../master';

import { AddStudentComponent,ViewStudentComponent,EditStudentComponent } from '../../student';
import { AlertModule } from 'ngx-bootstrap/alert';
import { AuthGuardGuard } from '../../core';
const routes: Routes = [
  // {
  //   path: '',
  //   pathMatch: 'full',
  //   canActivate: [AuthGuardGuard],
  //   component: DashboardComponent,
  // },
  {
    path: 'dashboard',
    component: DashboardComponent,
    canActivate: [AuthGuardGuard],
    resolve: {
      dashboard_details: DashboardResolver
   },
  },
  {
    path: 'student',
    component: StudentComponent,
    canActivate: [AuthGuardGuard],
  },
  {
    path: 'student/add',
    // canActivate: [AuthGuardGuard],
    component: AddStudentComponent,
  },
  {
    path: 'student/view',
    // canActivate: [AuthGuardGuard],
    component: ViewStudentComponent,
  },
  {
    path: 'master',
    component: MasterComponent,
    canActivate: [AuthGuardGuard],
    resolve: {
      master_details: MasterResolver
   },
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes),AlertModule.forRoot(),],
  exports: [RouterModule]
})
export class AdminLayoutRoutingModule { }
