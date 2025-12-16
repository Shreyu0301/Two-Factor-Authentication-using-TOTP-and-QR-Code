(function(){
  const key = 'theme-preference';
  const root = document.documentElement;
  function apply(theme){
    if(theme === 'dark') root.setAttribute('data-theme','dark');
    else root.removeAttribute('data-theme');
  }
  const stored = localStorage.getItem(key);
  if(stored){ apply(stored); }
  const btn = document.getElementById('themeToggle');
  if(btn){
    btn.addEventListener('click', function(){
      const current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      const next = current === 'dark' ? 'light' : 'dark';
      localStorage.setItem(key, next);
      apply(next);
    });
  }
})();
