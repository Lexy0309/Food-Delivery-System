package com.dinosoftlabs.foodies.android.Utils;

import android.graphics.Rect;
import android.support.v7.widget.RecyclerView;
import android.view.View;

/**
 * Created by Nabeel on 1/27/2018.
 */

public class SpacesItemDecoration extends RecyclerView.ItemDecoration {
    private int space;

    private final int bottomPadding;

    public SpacesItemDecoration(int bottomPadding) {
        this.bottomPadding = bottomPadding;
    }

    @Override
    public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
        int position = ((RecyclerView.LayoutParams) view.getLayoutParams()).getViewLayoutPosition();
        if (position == parent.getAdapter().getItemCount() - 1) {
            outRect.set(0, 0, 0, bottomPadding);
        }
    }
}