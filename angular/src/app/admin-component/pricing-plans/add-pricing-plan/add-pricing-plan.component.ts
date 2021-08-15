import { Component, OnInit } from '@angular/core';
import { UserService, AdminService } from '../../../core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-add-pricing-plan-profile',
  templateUrl: './add-pricing-plan.component.html',
  styleUrls: ['./add-pricing-plan.component.css']
})
export class AddPricingPlanComponent implements OnInit {
  public submitForm: FormGroup;
  public isSubmitting: any = false;
  public pricing_plans = [];
  public pricing_types = [
    {
      name: 'Individual',
      value: 'NON_AGENCY'
    },
    {
      name: 'Agency',
      value: 'AGENCY'
    }
  ];
  public campaign_limits = [
    {
      name: 'Please Select',
      value: ''
    },
    {
      name: '1',
      value: '1'
    },
    {
      name: '2',
      value: '2'
    },
    {
      name: '3',
      value: '3'
    },
    {
      name: '4',
      value: '4'
    },
    {
      name: '5',
      value: '5'
    },
    {
      name: 'No limit',
      value: 'NO'
    }
  ];
  public intervals = [
    {
      name: 'Month',
      value: 'month'
    },
    {
      name: 'Year',
      value: 'year'
    }
  ];
  public existing_pricing_plan_actions = [
    {
      name: 'Please Select',
      value: ''
    },
    {
      name: 'Retired',
      value: 'RETIRED'
    },
    {
      name: 'Migrate',
      value: 'MIGRATE'
    }
  ];
  constructor(
    private location: Location,
    public userService: UserService,
    public adminService: AdminService,
    public fb: FormBuilder,
    public router: Router,
  ) {
    this.submitForm = this.fb.group({
      'name': ['', [Validators.required]],
      'type': ['NON_AGENCY', [Validators.required]],
      'amount': ['', [Validators.required]],
      'interval': ['month', [Validators.required]],
      'note': ['', [Validators.required]],
      'discount': [''],
      'campaign_limit': ['', [Validators.required]],
      'target_plan_action_type': [''],
      'target_plan_id': ['']
    });
  }

  ngOnInit(): void {
    this.userService.page_title = 'Add Pricing Plan';
    this.getPricingPlansByType();
  }

  planTypeChange() {
    const discount_control = this.submitForm.get('discount');
    const campaign_limit_control = this.submitForm.get('campaign_limit');
    const target_plan_action_type_control = this.submitForm.get('target_plan_action_type');
    const target_plan_id_control = this.submitForm.get('target_plan_id');
    const is_type = this.submitForm.get('type');
    if (is_type.value === 'AGENCY') {
      discount_control.setValidators([Validators.required]);
      discount_control.updateValueAndValidity();
      campaign_limit_control.setValue('');
      campaign_limit_control.clearValidators();
      campaign_limit_control.updateValueAndValidity();
    } else {
      discount_control.setValue('');
      discount_control.clearValidators();
      discount_control.updateValueAndValidity();
      campaign_limit_control.setValidators([Validators.required]);
      campaign_limit_control.updateValueAndValidity();
    }
    target_plan_action_type_control.setValue('');
    target_plan_action_type_control.clearValidators();
    target_plan_action_type_control.updateValueAndValidity();
    target_plan_id_control.setValue('');
    target_plan_id_control.clearValidators();
    target_plan_id_control.updateValueAndValidity();
    this.getPricingPlansByType();
  }

  targetPlanActionChange() {
    const target_plan_id_control = this.submitForm.get('target_plan_id');
    const target_plan_action_type = this.submitForm.get('target_plan_action_type');
    if (target_plan_action_type.value) {
      target_plan_id_control.setValidators([Validators.required]);
      target_plan_id_control.updateValueAndValidity();
    } else {
      target_plan_id_control.setValue('');
      target_plan_id_control.clearValidators();
      target_plan_id_control.updateValueAndValidity();
    }
    this.getPricingPlansByType();
  }

  getPricingPlansByType() {
    const type = this.submitForm.get('type').value;
    this.adminService.getPricingPlansByType({type: type}).subscribe((response: any) => {
      this.pricing_plans = response.data || [];
      console.log(response);
    },
      err => {

      });
  }

  submit() {
    this.isSubmitting = true;
    this.adminService.createPricingPlan(this.submitForm.value).subscribe((response: any) => {
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.router.navigate(['/console/pricing-plans']);
    },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 4000
        });
        setTimeout(() => {
          this.isSubmitting = false;
          this.userService.alerts = [];
        }, 2000);
        this.isSubmitting = false;
      });
  }

  goToBack() {
    this.location.back();
  }
}
