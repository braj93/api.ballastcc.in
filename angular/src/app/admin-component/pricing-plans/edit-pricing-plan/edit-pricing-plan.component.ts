import { Component, OnInit, OnDestroy } from '@angular/core';
import { UserService, AdminService } from '../../../core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-edit-pricing-plan',
  templateUrl: './edit-pricing-plan.component.html',
  styleUrls: ['./edit-pricing-plan.component.css']
})
export class EditPricingPlanComponent implements OnInit, OnDestroy {
  public submitForm: FormGroup;
  public isSubmitting: any = false;
  public param_id: any;
  public form_details: any;
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
  constructor(
    private location: Location,
    public userService: UserService,
    public adminService: AdminService,
    public fb: FormBuilder,
    public router: Router,
    public route: ActivatedRoute,

  ) {
    this.submitForm = this.fb.group({
      'name': ['', [Validators.required]],
      'type': ['NON_AGENCY', [Validators.required]],
      'amount': ['', [Validators.required]],
      'interval': ['', [Validators.required]],
      'discount': [''],
      'note': ['', [Validators.required]]
    });
  }

  ngOnInit(): void {
    this.userService.page_title = 'Edit Pricing Plan';
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.route.data.subscribe((data: any) => {
      const result = data['details'];
      this.form_details = result.data;
    });
    this.submitForm = this.fb.group({
      'name': [this.form_details.name, [Validators.required]],
      'type': [this.form_details.type, [Validators.required]],
      'amount': [this.form_details.base_price, [Validators.required]],
      'interval': [this.form_details.interval, [Validators.required]],
      'discount': [this.form_details.discount, [Validators.required]],
      'note': [this.form_details.note, [Validators.required]]
    });

    const discount_control = this.submitForm.get('discount');
    const is_type = this.submitForm.get('type');
    if (is_type.value === 'AGENCY') {
      discount_control.setValidators([Validators.required]);
      discount_control.updateValueAndValidity();
    } else {
      discount_control.setValue('');
      discount_control.clearValidators();
      discount_control.updateValueAndValidity();
    }
  }

  submit() {
    this.isSubmitting = true;
    this.submitForm.value.pricing_plan_id = this.param_id;
    this.adminService.updatePricingPlan(this.submitForm.value).subscribe((response: any) => {
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 2000
      });
      this.router.navigate(['/console/pricing-plans']);
    },
      err => {
        this.userService.alerts.push({
          type: 'danger',
          msg: err.message,
          timeout: 2000
        });
        setTimeout(() => {
          this.isSubmitting = false;
          this.userService.alerts = [];
        }, 2000);
        this.isSubmitting = false;
      }
    );
  }

  goToBack() {
    this.location.back();
  }

  ngOnDestroy(): void {
    this.userService.alerts = [];
  }
}
