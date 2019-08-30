package com.dinosoftlabs.foodies.android.Utils;

import android.content.Context;
import android.util.AttributeSet;
import android.view.ViewGroup;
import android.widget.ExpandableListView;

/**
 * Created by Nabeel on 1/9/2018.
 */

public class CustomExpandableListView extends ExpandableListView {

    boolean expanded = false;

    public CustomExpandableListView (Context context) {
        super(context);
    }

    public CustomExpandableListView (Context context, AttributeSet attrs) {
        super(context, attrs);
    }

    public CustomExpandableListView (Context context, AttributeSet attrs,int defStyle) {
        super(context, attrs, defStyle);
    }

    public boolean isExpanded() {
        return expanded;
    }

    @Override
    public void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {

        if (isExpanded()) {
            int expandSpec = MeasureSpec.makeMeasureSpec(
                    Integer.MAX_VALUE >> 2, MeasureSpec.AT_MOST);
            super.onMeasure(widthMeasureSpec, expandSpec);
            ViewGroup.LayoutParams params = getLayoutParams();
            params.height = getMeasuredHeight();
        } else {
            super.onMeasure(widthMeasureSpec, heightMeasureSpec);
        }
    }

    public void setExpanded(boolean expanded) {
        this.expanded = expanded;
    }
}