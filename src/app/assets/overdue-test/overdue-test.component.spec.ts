import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OverdueTestComponent } from './overdue-test.component';

describe('OverdueTestComponent', () => {
  let component: OverdueTestComponent;
  let fixture: ComponentFixture<OverdueTestComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OverdueTestComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(OverdueTestComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
