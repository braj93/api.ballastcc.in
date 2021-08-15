import { Component, OnInit, ViewChild } from '@angular/core';
import { UserService, AdminService } from '../../../core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-add-category-profile',
  templateUrl: './add-category.component.html',
  styleUrls: ['./add-category.component.css']
})
export class AddCategoryComponent implements OnInit {
  public categoryForm: FormGroup;
  public isSubmitting: any = false;
  constructor(
    private location: Location,
    public userService: UserService,
    public adminService: AdminService,
    public fb: FormBuilder,
    public router: Router,
  ) {
    this.categoryForm = this.fb.group({
      'name': ['', [Validators.required]]
    });
  }

  ngOnInit(): void {
    this.userService.page_title = 'Add Category';
  }

  submitForm() {
    this.isSubmitting = true;
    this.adminService.createCategory(this.categoryForm.value).subscribe((response: any) => {
      this.isSubmitting = false;
      this.userService.alerts.push({
        type: 'success',
        msg: response.message,
        timeout: 4000
      });
      this.router.navigate(['/console/category']);
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
