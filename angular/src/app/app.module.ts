import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { AdminLayoutComponent } from './layouts/admin-layout/admin-layout.component';
import { StudentLayoutComponent } from './layouts/student-layout/student-layout.component';
import { TeacherLayoutComponent } from './layouts/teacher-layout/teacher-layout.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import {ComponentsModule} from './components/components.module';
import { RouterModule } from '@angular/router';
// backend import
import { CoreModule } from './core/core.module';
// backend import
import { SharedModule } from './shared';
import { HttpClientModule } from '@angular/common/http';
import {MatDatepickerModule} from '@angular/material/datepicker';
import { AlertModule } from 'ngx-bootstrap/alert';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { OnboardingLayoutComponent } from './layouts/onboarding-layout/onboarding-layout.component';
import { MasterComponent } from './admin-components/master/master.component';
import { FeeManagementComponent } from './admin-components/fee-management/fee-management.component';
import { AddPaymentComponent } from './admin-components/fee-management/add-payment/add-payment.component';
import { ViewPaymentComponent } from './admin-components/fee-management/view-payment/view-payment.component';
import { EditPaymentComponent } from './admin-components/fee-management/edit-payment/edit-payment.component';
// import {OnboardingLayoutModule} from './layouts/onboarding-layout/onboarding-layout.module';
@NgModule({
  declarations: [
    AppComponent,
    AdminLayoutComponent,
    StudentLayoutComponent,
    TeacherLayoutComponent,
    OnboardingLayoutComponent,
    MasterComponent,
    FeeManagementComponent,
    AddPaymentComponent,
    ViewPaymentComponent,
    EditPaymentComponent,
  ],
  imports: [
    CoreModule,
    SharedModule,
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    ComponentsModule,
    RouterModule,
    // OnboardingLayoutModule,
    AppRoutingModule,
    BrowserModule,
    NgxDatatableModule,
    HttpClientModule,
    MatDatepickerModule,
    AlertModule.forRoot(),
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
