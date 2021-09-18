import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { FormGroup, FormBuilder, Validators, FormControl, FormArray } from '@angular/forms';
import { AdminService } from '../../core';
import { Router } from '@angular/router';
@Component({
  selector: 'app-add-student',
  templateUrl: './add-student.component.html',
  styleUrls: ['./add-student.component.css']
})
export class AddStudentComponent implements OnInit {
  public isSubmitting: any = false;
  public submitFrom: FormGroup;
  public class_list: any = [];
  public board_list: any = [];
  public subjects_list: any = [];
  public batch_list: any = [];
  constructor(private location: Location,
    public router: Router,
    public fb: FormBuilder,
    public adminService: AdminService,) { 
      this.submitFrom = this.fb.group({
        'reg_number': ['', [Validators.required]],
        'reg_date': ['', [Validators.required]],
        'first_name': ['', [Validators.required]],
        'last_name': ['', [Validators.required]],
        'father_name': ['', [Validators.required]],
        'mother_name': ['', [Validators.required]],
        'class': ['', [Validators.required]],
        'batch': ['', [Validators.required]],
        'dob': ['', [Validators.required]],
        'board': ['', [Validators.required]],
        'medium': ['', [Validators.required]],
        'subjects': [[], [Validators.required]],
        'total_fee': ['', [Validators.required]],
        'remain_fee': ['', [Validators.required]],
        'email': ['', [Validators.email]],
        'school': ['', [Validators.required]],
        'mobile': ['', [Validators.minLength(10)]],
        'alt_mobile': ['', [Validators.minLength(10)]],
        'address': ['', [Validators.required]],
        'status': ['ACTIVE', [Validators.required]],
        
      });
    }

  ngOnInit(): void {
    this.adminService.page_title = 'Add Student';
    this.getClassList();
    this.getBatchList();
    this.getBoardList();
    this.getSubjectsList();
  }
  submitForm() {
    const form_values: any = this.submitFrom.value;
    console.log(form_values);
    // let is_selcted = true;
    // // tslint:disable-next-line:forin
    // for (var i in form_values) {
    //   if (form_values[i] === true) {
    //     form_values[i] = 'YES';
    //     is_selcted = false;
    //   }
    //   if (form_values[i] === false) {
    //     form_values[i] = 'NO';
    //   }
    // }
    // if (is_selcted) {
    //   this.userService.alerts.push({
    //     type: 'danger',
    //     msg: 'Please Select Campaign Features',
    //     timeout: 2000
    //   });
    // return;
    // } else {
    //   this.isSubmitting = true;
    //   this.campaignService.addCampaign(form_values).subscribe((response: any) => {
    //     const data = response.data;
    //     this.isSubmitting = false;
    //     this.userService.alerts.push({
    //       type: 'success',
    //       msg: response.message,
    //       timeout: 4000
    //     });
    //     if (form_values.is_call_tracking_number === 'YES') {
    //       this.router.navigate(['/user/campaigns/' + data.campaign_guid + '/call-tracking-number']);
    //     } else if (form_values.is_landing_page === 'YES') {
    //       this.router.navigate(['/user/campaigns/' + data.campaign_guid + '/templates']);
    //     } else {
    //       this.router.navigate(['/user/campaigns/' + data.campaign_guid + '/qr-code']);
    //     }
    //   },
    //   err => {
    //     this.userService.alerts.push({
    //       type: 'danger',
    //       msg: err.message,
    //       timeout: 4000
    //     });
    //     this.isSubmitting = false;
    //   });
    // }
  }
  get getControl(){
    return this.submitFrom.controls;
  }
  getClassList(){
    this.adminService.getClassList().subscribe((response: any) => {
      this.class_list = response.data;
      console.log(this.class_list);
    }, err => {
      console.log(err.message);
    });
  }
  getSubjectsList(){
    this.adminService.getClassList().subscribe((response: any) => {
      this.subjects_list = response.data;
      console.log(this.class_list);
    }, err => {
      console.log(err.message);
    });
  }
  getBatchList(){
    this.adminService.getClassList().subscribe((response: any) => {
      this.batch_list = response.data;
      console.log(this.class_list);
    }, err => {
      console.log(err.message);
    });
  }
  getBoardList(){
    this.adminService.getClassList().subscribe((response: any) => {
      this.board_list = response.data;
      console.log(this.class_list);
    }, err => {
      console.log(err.message);
    });
  }

  goToBack(){
    this.location.back();
}
}
