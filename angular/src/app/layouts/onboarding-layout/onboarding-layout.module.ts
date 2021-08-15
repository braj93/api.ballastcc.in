import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { OnboardingLayoutComponent } from './onboarding-layout.component';
import { SetNewPasswordComponent } from './set-new-password.component';
import {MatButtonModule} from '@angular/material/button';
import {MatInputModule} from '@angular/material/input';
import {MatRippleModule} from '@angular/material/core';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatTooltipModule} from '@angular/material/tooltip';
import {MatSelectModule} from '@angular/material/select';
import { SharedModule } from '../../shared';
import { OnBoardingLayoutRoutes } from './onboarding-layout.routing';
import { AlertModule  } from 'ngx-bootstrap'; 
@NgModule({
  imports: [
    SharedModule,
    CommonModule,
    AlertModule.forRoot(),
    RouterModule.forChild(OnBoardingLayoutRoutes),
    FormsModule,
    ReactiveFormsModule,
    MatButtonModule,
    MatRippleModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatTooltipModule,
  ],
  declarations: [
    OnboardingLayoutComponent,
    SetNewPasswordComponent
  ]
})

export class OnBoardingLayoutModule {}
