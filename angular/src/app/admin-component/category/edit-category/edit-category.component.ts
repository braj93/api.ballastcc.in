import { Component, OnInit, OnDestroy } from '@angular/core';
import { UserService, AdminService } from '../../../core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-edit-category-profile',
  templateUrl: './edit-category.component.html',
  styleUrls: ['./edit-category.component.css']
})
export class EditCategoryComponent implements OnInit, OnDestroy {
  public categoryForm: FormGroup;
  public isSubmitting: any = false;
  public param_id: any;
  public form_details: any;
  constructor(
    private location: Location,
    public userService: UserService,
    public adminService: AdminService,
    public fb: FormBuilder,
    public router: Router,
    public route: ActivatedRoute,

  ) {
    this.categoryForm = this.fb.group({
      'name': ['', [Validators.required]]
    });
  }

  ngOnInit(): void {
    this.userService.page_title = 'Edit Category';
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.route.data.subscribe((data: any) => {
      const result = data['get_detail_by_id'];
      this.form_details = result.data;
    });

    this.categoryForm = this.fb.group({
      'name': [this.form_details.name, [Validators.required]],
    });
  }
  submitForm() {
    this.isSubmitting = true;
    this.categoryForm.value.category_id = this.param_id;
    this.adminService.updateCategory(this.categoryForm.value).subscribe((response: any) => {
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 2000
      });
      this.router.navigate(['/console/category']);
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
