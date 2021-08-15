import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatButtonModule } from '@angular/material/button';
import { MatInputModule } from '@angular/material/input';
import { MatRippleModule } from '@angular/material/core';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatTooltipModule } from '@angular/material/tooltip';
import { MatSelectModule } from '@angular/material/select';
import { DataTablesModule } from 'angular-datatables';
import { NgxDatatableModule } from '@swimlane/ngx-datatable';
import { SharedModule } from '../../shared';
import { AlertModule  } from 'ngx-bootstrap';

import { TabsModule } from 'ngx-bootstrap/tabs';

import { UserLayoutRoutes } from './user-layout.routing';
import { DomSanitizerPipe } from '../../pipes/dom-sanitizer.pipe';
import { CampaignComponent, AddCampaignComponent, EditCampaignComponent, CampaignDetailByIdResolver, CampaignSettingComponent, CampaignStepTwoComponent, ViewCampaignComponent, ViewCampaignDetailByIdResolver, CampaignCallTrackingComponent, CampaignCallTrackingResolver, CampaignReportDetailResolver } from '../../campaign';
import { LandingpageComponent } from '../../landingpage';
import { TemplateComponent, TemplateDetailResolver } from '../../template';
import { BsDatepickerModule } from 'ngx-bootstrap/datepicker';
import { RealEstateTemplateComponent, FitnessTemplateComponent, RetailTemplateComponent, RestaurantTemplateComponent, EventTemplateComponent, AutomotiveTemplateComponent, MediaTemplateComponent, RealEstateTwoTemplateComponent, SimpleTemplateComponent, DetailedTemplateComponent } from '../../templates';
import { ImageCropModule } from '../../image-cropper/image-crop.module';
import {
  UserPlanUpdateComponent,
  UserDashboardResolver,
  UserDashboardComponent,
  MyBusinessComponent,
  UserCrmComponent,
  UserBillingComponent,
  UserBillingResolver,
  UserCrmResolver,
  UserKnowledgebaseComponent,
  UserKnowledgebaseResolver,
  UserKnowledgebaseDetailComponent,
  UserKnowledgebaseDetailResolver,
  ViewProileComponent,
  ProfileSettingsComponent,
  ChangePasswordComponent,
  NoteAddComponent, ContactDetailResolver,
  UserBroadcastComponent,
  ViewBroadcastComponent,
  ViewBroadcastDetailResolver,
  MembersComponent,
  UserDetailsResolver,
  UserHelpComponent
} from '../../user-component';
import { PhoneFormatModule  } from '../../pipes/phone-format.module';
@NgModule({
  imports: [
    PhoneFormatModule,
    ImageCropModule,
    SharedModule,
    AlertModule,
    DataTablesModule,
    CommonModule,
    RouterModule.forChild(UserLayoutRoutes),
    FormsModule,
    ReactiveFormsModule,
    MatButtonModule,
    MatRippleModule,
    MatFormFieldModule,
    MatInputModule,
    MatSelectModule,
    MatTooltipModule,
    NgxDatatableModule,
    BsDatepickerModule.forRoot(),
    TabsModule.forRoot()
  ],
  declarations: [
    UserPlanUpdateComponent,
    DomSanitizerPipe,
    UserDashboardComponent,
    MyBusinessComponent,
    UserCrmComponent,
    UserBillingComponent,
    UserKnowledgebaseComponent,
    UserKnowledgebaseDetailComponent,
    ViewProileComponent,
    ProfileSettingsComponent,
    ChangePasswordComponent,
    NoteAddComponent,
    UserBroadcastComponent,
    ViewBroadcastComponent,
    CampaignComponent,
    AddCampaignComponent,
    EditCampaignComponent,
    CampaignSettingComponent,
    CampaignStepTwoComponent,
    CampaignCallTrackingComponent,
    ViewCampaignComponent,
    LandingpageComponent,
    TemplateComponent,
    RealEstateTemplateComponent,
    FitnessTemplateComponent,
    RetailTemplateComponent,
    RestaurantTemplateComponent,
    EventTemplateComponent,
    SimpleTemplateComponent,
    DetailedTemplateComponent,
    AutomotiveTemplateComponent,
    MediaTemplateComponent,
    RealEstateTwoTemplateComponent,
    MembersComponent,
    UserHelpComponent
  ],
  providers: [
    UserDashboardResolver,
    DomSanitizerPipe,
    UserBillingResolver,
    UserCrmResolver,
    UserKnowledgebaseResolver,
    CampaignCallTrackingResolver,
    UserKnowledgebaseDetailResolver,
    ViewBroadcastDetailResolver,
    ContactDetailResolver,
    CampaignDetailByIdResolver,
    ViewCampaignDetailByIdResolver,
    CampaignReportDetailResolver,
    TemplateDetailResolver,
    UserDetailsResolver
  ]
})

export class UsersLayoutModule {}
