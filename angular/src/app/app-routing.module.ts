import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {AdminLayoutComponent} from './layouts/admin-layout/admin-layout.component';
import {OnboardingLayoutComponent} from './layouts/onboarding-layout/onboarding-layout.component';
import {OnboardingLayoutModule} from './layouts/onboarding-layout/onboarding-layout.module';
const routes: Routes = [
  {
    path: '',
    redirectTo: 'users',
    pathMatch: 'full',
  },
  {
    path:'users',
    
    loadChildren: () => import('./layouts/onboarding-layout/onboarding-layout.module')
    .then(m => m.OnboardingLayoutModule),
    component:OnboardingLayoutComponent,
  },
  {
    path:'admin',
    component:AdminLayoutComponent,
    loadChildren: () => import('./layouts/admin-layout/admin-layout.module')
    .then(m => m.AdminLayoutModule),
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
