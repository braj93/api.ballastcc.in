import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserKnowledgebaseComponent } from './user-knowledgebase.component';

describe('UserKnowledgebaseComponent', () => {
  let component: UserKnowledgebaseComponent;
  let fixture: ComponentFixture<UserKnowledgebaseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserKnowledgebaseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserKnowledgebaseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
