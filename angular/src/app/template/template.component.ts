import {Component, OnInit} from '@angular/core';
import { UserService } from '../core';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-edit-template',
  templateUrl: './template.component.html',
  styleUrls: ['./template.component.css']
})
export class TemplateComponent implements OnInit{
  public isSubmitting: boolean = false;
  public alerts: any = [];
  public rows: any = [];
  public isSelected: boolean = false;
  public loading: boolean = false;
  public details: any;
  constructor(
  	public userService: UserService,
    public router: Router,
    public route: ActivatedRoute,

  	){}

  ngOnInit(): void {
    this.userService.page_title = "Template";
    this.route.data.subscribe((data:any) => {
      let result = data['detail'];
      this.details = result.data;
    });
  }

  ngOnDestroy(): void{
    this.userService.alerts = [];
  }

}