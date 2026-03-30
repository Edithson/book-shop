<!-- Chart + Recent activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

          <!-- Bar chart -->
          <div class="lg:col-span-2 stat-card p-5">
            <div class="flex items-center justify-between mb-5">
              <h2 class="font-serif font-bold text-base">Téléchargements — 7 derniers jours</h2>
              <span class="text-xs text-ink/40">Cette semaine</span>
            </div>
            <div class="flex items-end gap-2 h-36">
              <template x-for="(bar, i) in chartData" :key="i">
                <div class="flex-1 flex flex-col items-center gap-1">
                  <div class="w-full rounded-t-md chart-bar"
                    :style="`height: ${bar.pct}%; background: ${bar.active ? 'var(--amber)' : 'rgba(200,136,58,0.2)'}`"></div>
                  <span class="text-xs text-ink/30" x-text="bar.day"></span>
                </div>
              </template>
            </div>
          </div>

          <!-- Top books -->
          <div class="stat-card p-5">
            <h2 class="font-serif font-bold text-base mb-4">Top livres</h2>
            <div class="space-y-3">
              <template x-for="(book, i) in topBooks" :key="i">
                <div class="flex items-center gap-3">
                  <span class="text-xl flex-shrink-0" x-text="book.icon"></span>
                  <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium truncate" x-text="book.title"></div>
                    <div class="h-1.5 mt-1 rounded-full bg-parchment overflow-hidden">
                      <div class="h-full rounded-full bg-amber chart-bar" :style="`width: ${book.pct}%`"></div>
                    </div>
                  </div>
                  <span class="text-xs text-ink/40 flex-shrink-0" x-text="book.dl"></span>
                </div>
              </template>
            </div>
          </div>
        </div>
