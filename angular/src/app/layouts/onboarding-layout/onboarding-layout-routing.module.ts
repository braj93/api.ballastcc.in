import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { OnboardingLayoutComponent } from './onboarding-layout.component';
import { NoAuthGuardGuard } from '../../core'; 
const routes: Routes = [
  {
    path: '',
    redirectTo: 'login',
},
{
    path: 'login',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard]
},
{ 
    path: 'signup-individual/:code',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard]
},
{ 
    path: 'signup-agency/:code',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard] 
},
{ 
    path: 'signup-agency-member/:code',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard]
},
{ 
    path: 'signup-team-member/:code',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard]
},
{ 
    path: 'payment',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard]
},
{ 
    path: 'signup-success',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard]
},
{ 
    path: 'forgot-password',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard] 
},
{ 
    path: 'set-new-password/:code',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard] 
},
{ 
    path: 'password-success',
    component: OnboardingLayoutComponent,
    canActivate: [NoAuthGuardGuard] 
}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class OnboardingLayoutRoutingModule { }
