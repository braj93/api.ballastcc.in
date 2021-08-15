import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserKnowledgebaseDetailComponent } from './user-knowledgebase-detail.component';

describe('UserKnowledgebaseDetailComponent', () => {
  let component: UserKnowledgebaseDetailComponent;
  let fixture: ComponentFixture<UserKnowledgebaseDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserKnowledgebaseDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserKnowledgebaseDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
