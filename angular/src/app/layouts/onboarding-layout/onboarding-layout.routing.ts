import { Routes } from '@angular/router';
import { OnboardingLayoutComponent } from './onboarding-layout.component';
import { NoAuthGuard } from '../../core'; 
export const OnBoardingLayoutRoutes: Routes = [
    {
        path: '',
        redirectTo: 'login',
    },
    {
        path: 'login',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard]
    },
    { 
        path: 'signup-individual/:code',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard]
    },
    { 
        path: 'signup-agency/:code',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard] 
    },
    { 
        path: 'signup-agency-member/:code',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard]
    },
    { 
        path: 'signup-team-member/:code',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard]
    },
    { 
        path: 'payment',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard]
    },
    { 
        path: 'signup-success',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard]
    },
    { 
        path: 'forgot-password',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard] 
    },
    { 
        path: 'set-new-password/:code',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard] 
    },
    { 
        path: 'password-success',
        component: OnboardingLayoutComponent,
        canActivate: [NoAuthGuard] 
    }
];
