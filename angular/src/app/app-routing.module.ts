import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {LoginComponent} from './login/login.component';
import {DashboardComponent} from './dashboard/dashboard.component';
import {AdminLayoutComponent} from './layouts/admin-layout/admin-layout.component';

const routes: Routes = [
  {
    path:'',
    redirectTo: 'login',
    pathMatch: 'full',
  },
  {
    path:'login',
    component:LoginComponent
  },
  {
    path:'admin',
    component:AdminLayoutComponent,
    loadChildren: () => import('./layouts/admin-layout/admin-layout.module')
    .then(m => m.AdminLayoutModule),
    // children: [{
    //   path: '',
    //   loadChildren: './layouts/admin-layout/admin-layout.module#AdminLayoutModule'
    // }]
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
