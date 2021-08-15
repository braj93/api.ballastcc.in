import {Component, OnInit} from '@angular/core';
import { UserService, AdminService } from '../../core';
import { BsModalService } from 'ngx-bootstrap/modal';
import { ActivatedRoute, Router, NavigationEnd, Event } from '@angular/router';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { Location } from '@angular/common';

declare var $;

@Component({
  selector: 'app-user-knowledgebase-detail',
  templateUrl: './user-knowledgebase-detail.component.html',
  styleUrls: ['./user-knowledgebase-detail.component.scss']
})
export class UserKnowledgebaseDetailComponent implements OnInit{

  public detail: any;
  public param_id: any;
  public related_stuffs: any = [];
  constructor(
      public location: Location,
      public userService: UserService,
      public adminService: AdminService,
      public modalService: BsModalService,
      public route: ActivatedRoute,
      public router: Router,
      public sanitizer: DomSanitizer
    ){

    router.events.subscribe((event: Event) => {
      if (event instanceof NavigationEnd) {
        this.param_id = this.route.snapshot.paramMap.get('id');
        this.getRelatedStuffsList();
      }
    });

  }

  ngOnInit(): void {
    this.userService.page_title = "Knowledge Base Detail";
    this.route.params.subscribe(params => {
      this.param_id = params.id;
    });
    this.route.data.subscribe((data:any) => {
      let result = data['user_knowledgebase_detail'];
      this.detail = result.data;
    });
      // console.log("this.detail", this.detail);
      this.getRelatedStuffsList();


  }

  getRelatedStuffsList(){
    this.adminService.userKnowledgebaseRelatedStuffList({knowledgebase_id: this.param_id}).subscribe((response: any) => {
      this.related_stuffs = response.data;
    });
  }

  formattedText(str){
    return str.slice(0, 153)+ ' .....';
  }

  goToBack() {
    this.location.back(); 
  }

}