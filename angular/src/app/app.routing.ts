import { NgModule } from '@angular/core';
import { CommonModule, } from '@angular/common';
import { BrowserModule  } from '@angular/platform-browser';
import { Routes, RouterModule } from '@angular/router';

import { AdminLayoutComponent } from './layouts/admin-layout/admin-layout.component';
import { UserLayoutComponent } from './layouts/user-layout/user-layout.component';

const routes: Routes =[
  {
    path: '',
    redirectTo: 'users',
    pathMatch: 'full',
  },
  {
    path: 'users',
    // component: OnboardingLayoutComponent,
    children: [{
      path: '',
      loadChildren: './layouts/onboarding-layout/onboarding-layout.module#OnBoardingLayoutModule'
    }]
  },
  {
    path: 'console',
    component: AdminLayoutComponent,
    children: [{
      path: '',
      loadChildren: './layouts/admin-layout/admin-layout.module#AdminLayoutModule'
    }]
  },
  {
    path: 'siteuser',
    component: AdminLayoutComponent,
    children: [{
      path: '',
      loadChildren: './layouts/admin-layout/admin-layout.module#AdminLayoutModule'
    }]
  },
  {
    path: 'user',
    component: UserLayoutComponent,
    children: [{
      path: '',
      loadChildren: './layouts/user-layout/user-layout.module#UsersLayoutModule'
    }]
  }
];
@NgModule({
  imports: [
    CommonModule,
    BrowserModule,
    RouterModule.forRoot(routes,{
       useHash: false
    })
  ],
  exports: [
  ],
})
export class AppRoutingModule { }
