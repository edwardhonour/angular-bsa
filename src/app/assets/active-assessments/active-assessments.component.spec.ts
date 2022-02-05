import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ActiveAssessmentsComponent } from './active-assessments.component';

describe('ActiveAssessmentsComponent', () => {
  let component: ActiveAssessmentsComponent;
  let fixture: ComponentFixture<ActiveAssessmentsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ActiveAssessmentsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ActiveAssessmentsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
