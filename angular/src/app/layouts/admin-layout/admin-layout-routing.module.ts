import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { DashboardComponent,DashboardResolver } from '../../admin-components/dashboard';
import { StudentComponent } from '../../admin-components/student';
import { MasterComponent,MasterResolver } from '../../admin-components/master';
import {FeeManagementComponent, AddPaymentComponent,ViewPaymentComponent,EditPaymentComponent} from '../../admin-components/fee-management';
import { AddStudentComponent,ViewStudentComponent,EditStudentComponent } from '../../admin-components/student';
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
    path: 'student/:id/edit',
    // canActivate: [AuthGuardGuard],
    component: EditStudentComponent,
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
  {
    path: 'fee-management',
    component: FeeManagementComponent,
    canActivate: [AuthGuardGuard],
  },
  {
    path: 'payment/:id/edit',
    // canActivate: [AuthGuardGuard],
    component: EditPaymentComponent,
  },
  {
    path: 'payment/:id/view',
    // canActivate: [AuthGuardGuard],
    component: ViewPaymentComponent,
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes),AlertModule.forRoot(),],
  exports: [RouterModule]
})
export class AdminLayoutRoutingModule { }
